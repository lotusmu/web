<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ConvertVoltToTheme extends Command
{
    protected $signature = 'theme:convert-volt {path} {--theme=default} {--dry-run}';

    protected $description = 'Convert a Volt component to Livewire class + theme blade file';

    private array $phpImports = [];

    private string $phpLogic = '';

    private string $bladeContent = '';

    public function handle()
    {
        $voltPath = $this->argument('path');
        $theme = $this->option('theme');
        $dryRun = $this->option('dry-run');

        // Reset properties for each run
        $this->phpImports = [];
        $this->phpLogic = '';
        $this->bladeContent = '';

        // Validate input
        if (! File::exists($voltPath)) {
            $this->error("File not found: {$voltPath}");

            return 1;
        }

        if (! str_contains($voltPath, 'resources/views/livewire/pages/')) {
            $this->error('This command only works with files in resources/views/livewire/pages/');

            return 1;
        }

        $this->info("Converting: {$voltPath}");

        // Parse the Volt file
        if (! $this->parseVoltFile($voltPath)) {
            return 1;
        }

        // Generate the file paths and class names
        $paths = $this->generatePaths($voltPath, $theme);

        // Generate the Livewire class content
        $livewireContent = $this->generateLivewireClass($paths);

        // Clean up blade content
        $cleanBladeContent = $this->cleanBladeContent();

        // Display what will be created
        $this->displayPreview($paths, $livewireContent, $cleanBladeContent);

        if ($dryRun) {
            $this->info('Dry run complete. No files were created.');

            return 0;
        }

        // Confirm before proceeding
        if (! $this->confirm('Create these files?')) {
            $this->info('Conversion cancelled.');

            return 0;
        }

        // Create the files
        $this->createFiles($paths, $livewireContent, $cleanBladeContent, $voltPath);

        $this->info('✅ Conversion complete!');
        $this->info("📝 Don't forget to:");
        $this->info('   1. Update routes if this is a page component');
        $this->info('   2. Update component references in other files');
        $this->info('   3. Copy theme file to other themes if needed');

        return 0;
    }

    private function parseVoltFile(string $path): bool
    {
        $content = File::get($path);

        // Check if it's a Volt file
        if (! str_contains($content, 'new class extends Component') && ! str_contains($content, 'new #[Layout') && ! str_contains($content, 'Livewire\Volt\Component')) {
            $this->error("This doesn't appear to be a Volt component file.");

            return false;
        }

        // Split PHP and Blade content
        if (preg_match('/^<\?php(.*?)\?>\s*(.*)$/s', $content, $matches)) {
            $phpSection = '<?php'.$matches[1].'?>';
            $this->bladeContent = trim($matches[2]);
        } else {
            $this->error('Could not parse Volt file structure.');

            return false;
        }

        // Extract PHP imports and logic
        $this->extractPhpContent($phpSection);

        return true;
    }

    private function extractPhpContent(string $phpSection): void
    {
        $lines = explode("\n", $phpSection);
        $inClassDefinition = false;
        $braceCount = 0;
        $classContent = [];
        $skipNextLine = false;

        foreach ($lines as $lineIndex => $line) {
            $trimmed = trim($line);

            // Skip opening PHP tag and empty lines at start
            if ($trimmed === '<?php' || ($trimmed === '' && ! $inClassDefinition)) {
                continue;
            }

            // Skip closing PHP tag
            if ($trimmed === '?>' || str_contains($trimmed, '?>')) {
                continue;
            }

            // Collect imports (use statements)
            if (str_starts_with($trimmed, 'use ')) {
                $this->phpImports[] = $trimmed;

                continue;
            }

            // Find class definition start - handle both patterns
            if ((str_contains($trimmed, 'new class extends Component') ||
                 str_contains($trimmed, 'new #[Layout') ||
                 str_contains($line, 'class extends Component')) &&
                ! $inClassDefinition) {
                $inClassDefinition = true;
                // If the opening brace is on the same line, count it
                if (str_contains($line, '{')) {
                    $braceCount += substr_count($line, '{') - substr_count($line, '}');
                }

                continue;
            }

            // Collect class content
            if ($inClassDefinition) {
                // Count braces to find class end
                $openBraces = substr_count($line, '{');
                $closeBraces = substr_count($line, '}');
                $braceCount += $openBraces - $closeBraces;

                // Skip the closing }; line
                if ($braceCount <= 0 && (str_contains($trimmed, '};') || $trimmed === '}')) {
                    break;
                }

                // Skip empty lines at the start of class
                if ($trimmed === '' && empty($classContent)) {
                    continue;
                }

                $classContent[] = $line;
            }
        }

        $this->phpLogic = trim(implode("\n", $classContent));
    }

    private function generatePaths(string $voltPath, string $theme): array
    {
        // Convert absolute path to relative path from project root
        $projectRoot = base_path();
        if (str_starts_with($voltPath, $projectRoot)) {
            $voltPath = substr($voltPath, strlen($projectRoot) + 1);
        }

        // Convert path to namespace and class name
        // e.g., resources/views/livewire/pages/guest/catalog/buffs.blade.php
        $relativePath = str_replace([
            'resources/views/livewire/pages/',
            '.blade.php',
        ], '', $voltPath);

        $parts = explode('/', $relativePath);
        $fileName = array_pop($parts);

        // Custom naming logic based on requirements
        if ($fileName === 'index') {
            // For index.blade.php files, use parent directory name as class name
            $className = ! empty($parts) ? Str::studly(end($parts)) : 'Index';
        } else {
            // For other files, use the filename as class name
            $className = Str::studly($fileName);
        }

        // Determine namespace and directory structure
        if (empty($parts)) {
            // Direct files under livewire/pages/ go to App namespace
            $namespace = 'App\\Livewire\\Pages\\App';
            $namespaceParts = ['App'];
        } else {
            // Files in subdirectories
            if ($fileName === 'index') {
                // For index files, don't include the parent folder in namespace since we use it as class name
                $namespaceParts = array_slice($parts, 0, -1);
            } else {
                // For non-index files, include all parts
                $namespaceParts = $parts;
            }

            if (empty($namespaceParts)) {
                $namespace = 'App\\Livewire\\Pages\\App';
                $namespaceParts = ['App'];
            } else {
                $namespace = 'App\\Livewire\\Pages\\'.implode('\\', array_map([Str::class, 'studly'], $namespaceParts));
            }
        }

        // Generate file paths
        $livewireDir = app_path('Livewire/Pages/'.implode('/', array_map([Str::class, 'studly'], $namespaceParts)));
        $livewireFile = $livewireDir.'/'.$className.'.php';

        $themeDir = resource_path("views/themes/{$theme}/pages/".implode('/', $parts));
        $themeFile = $themeDir.'/'.$fileName.'.blade.php';

        $viewName = 'pages.'.implode('.', $parts).'.'.$fileName;

        return [
            'namespace' => $namespace,
            'className' => $className,
            'livewireDir' => $livewireDir,
            'livewireFile' => $livewireFile,
            'themeDir' => $themeDir,
            'themeFile' => $themeFile,
            'viewName' => $viewName,
            'layoutType' => (! empty($parts) && $parts[0] === 'guest') ? 'guest' : 'app',
        ];
    }

    private function generateLivewireClass(array $paths): string
    {
        // Clean up imports - remove Layout and Volt specific imports
        $cleanImports = [];
        foreach ($this->phpImports as $import) {
            // Skip Volt-specific imports
            if (str_contains($import, 'Livewire\Volt\Component') ||
                str_contains($import, 'Livewire\Attributes\Layout')) {
                continue;
            }
            $cleanImports[] = $import;
        }

        // Add BaseComponent import if not present
        $hasBaseComponent = false;
        foreach ($cleanImports as $import) {
            if (str_contains($import, 'BaseComponent')) {
                $hasBaseComponent = true;
                break;
            }
        }

        if (! $hasBaseComponent) {
            $cleanImports[] = 'use App\\Livewire\\BaseComponent;';
        }

        $imports = implode("\n", $cleanImports);

        // Ensure proper formatting for PHP logic
        $formattedLogic = '';
        if (! empty($this->phpLogic)) {
            $lines = explode("\n", $this->phpLogic);
            $indentedLines = array_map(function ($line) {
                return empty(trim($line)) ? '' : '    '.$line;
            }, $lines);
            $formattedLogic = implode("\n", $indentedLines);
        }

        return "<?php

namespace {$paths['namespace']};

{$imports}

class {$paths['className']} extends BaseComponent
{
{$formattedLogic}

    protected function getViewName(): string
    {
        return '{$paths['viewName']}';
    }

    protected function getLayoutType(): string
    {
        return '{$paths['layoutType']}';
    }
}";
    }

    private function cleanBladeContent(): string
    {
        $content = $this->bladeContent;

        // Find any @php blocks that might contain imports and add them at the top
        $phpBlocks = [];
        $content = preg_replace_callback(
            '/@php\s*(.*?)@endphp/s',
            function ($matches) use (&$phpBlocks) {
                $block = trim($matches[1]);
                if (! empty($block)) {
                    $phpBlocks[] = $block;
                }

                return '';
            },
            $content
        );

        // Add PHP imports from the original Volt file if they're needed in the blade
        $neededImports = [];
        foreach ($this->phpImports as $import) {
            $className = last(explode('\\', str_replace(['use ', ';'], '', $import)));
            if (str_contains($content, $className.'::') || str_contains($content, $className.'->')) {
                $neededImports[] = $import;
            }
        }

        $allPhpContent = array_merge($neededImports, $phpBlocks);

        if (! empty($allPhpContent)) {
            $content = "@php\n".implode("\n", $allPhpContent)."\n@endphp\n\n".$content;
        }

        return trim($content);
    }

    private function displayPreview(array $paths, string $livewireContent, string $cleanBladeContent): void
    {
        $this->info('📁 Will create Livewire class:');
        $this->line("   {$paths['livewireFile']}");

        $this->info('📁 Will create theme blade file:');
        $this->line("   {$paths['themeFile']}");

        $this->newLine();
        $this->info('📋 Livewire class preview:');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->line(Str::limit($livewireContent, 500));

        $this->newLine();
        $this->info('📋 Theme blade preview:');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->line(Str::limit($cleanBladeContent, 300));
        $this->newLine();
    }

    private function createFiles(array $paths, string $livewireContent, string $cleanBladeContent, string $originalPath): void
    {
        // Create Livewire class
        File::ensureDirectoryExists($paths['livewireDir']);
        File::put($paths['livewireFile'], $livewireContent);
        $this->info("✅ Created: {$paths['livewireFile']}");

        // Create theme blade file
        File::ensureDirectoryExists($paths['themeDir']);
        File::put($paths['themeFile'], $cleanBladeContent);
        $this->info("✅ Created: {$paths['themeFile']}");

        // Optionally remove original file
        if ($this->confirm("Remove original Volt file: {$originalPath}?")) {
            File::delete($originalPath);
            $this->info("🗑️  Removed: {$originalPath}");
        }
    }
}
