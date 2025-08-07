<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ConvertVoltBatch extends Command
{
    protected $signature = 'theme:convert-volt-batch {directory} {--theme=default} {--dry-run} {--namespace=}';

    protected $description = 'Convert all Volt components in a directory to Livewire + theme files';

    public function handle()
    {
        $directory = $this->argument('directory');
        $theme = $this->option('theme');
        $dryRun = $this->option('dry-run');

        if (! File::isDirectory($directory)) {
            $this->error("Directory not found: {$directory}");

            return 1;
        }

        // Find all Volt files
        $voltFiles = $this->findVoltFiles($directory);

        if (empty($voltFiles)) {
            $this->info("No Volt files found in: {$directory}");

            return 0;
        }

        $this->info('Found '.count($voltFiles).' Volt files:');
        foreach ($voltFiles as $file) {
            $this->line('  • '.$file);
        }

        if (! $this->confirm('Convert all these files?')) {
            $this->info('Batch conversion cancelled.');

            return 0;
        }

        // Get namespace selection
        $selectedNamespace = $this->getNamespaceSelection();

        // Convert each file
        $successful = 0;
        $failed = 0;

        foreach ($voltFiles as $file) {
            $this->newLine();
            $this->info('Converting: '.basename($file));

            $exitCode = $this->call('theme:convert-volt', [
                'path' => $file,
                '--theme' => $theme,
                '--dry-run' => $dryRun,
                '--namespace' => $selectedNamespace,
            ]);

            if ($exitCode === 0) {
                $successful++;
            } else {
                $failed++;
                $this->error("Failed to convert: {$file}");
            }
        }

        $this->newLine();
        $this->info('Batch conversion complete:');
        $this->info("✅ Successful: {$successful}");
        if ($failed > 0) {
            $this->error("❌ Failed: {$failed}");
        }

        return $failed > 0 ? 1 : 0;
    }

    private function findVoltFiles(string $directory): array
    {
        $files = [];

        foreach (File::allFiles($directory) as $file) {
            if (str_ends_with($file->getFilename(), '.blade.php')) {
                $content = File::get($file->getRealPath());

                // Check if it's a Volt file (handle both patterns)
                if (str_contains($content, 'class extends Component') &&
                    (str_contains($content, 'use Livewire\Volt\Component') || str_contains($content, 'Volt\Component'))) {
                    $files[] = $file->getRealPath();
                }
            }
        }

        return $files;
    }

    private function getNamespaceSelection(): string
    {
        // Check if namespace was provided via option
        $namespaceOption = $this->option('namespace');
        if ($namespaceOption) {
            return $namespaceOption;
        }

        $this->newLine();
        $this->info('🗂️  Select target namespace for Livewire components:');

        // Scan existing Livewire/Pages directories
        $livewirePagesPath = app_path('Livewire/Pages');
        $existingFolders = [];

        if (File::isDirectory($livewirePagesPath)) {
            $directories = File::directories($livewirePagesPath);
            foreach ($directories as $dir) {
                $folderName = basename($dir);
                $existingFolders[] = $folderName;
            }
        }

        // Build options list
        $options = [];
        $choices = [];

        // Add existing folders
        if (! empty($existingFolders)) {
            foreach ($existingFolders as $folder) {
                $options[] = "App\\Livewire\\Pages\\{$folder}";
                $choices[] = "📁 {$folder} (existing)";
            }
        }

        // Add option to create new
        $options[] = 'CREATE_NEW';
        $choices[] = '✨ Create new folder';

        // If no existing folders, default to App
        if (empty($existingFolders)) {
            $options[] = 'App\\Livewire\\Pages\\App';
            $choices[] = '📁 App (default)';
        }

        $selectedIndex = $this->choice('Choose namespace:', $choices, 0);
        $selectedOption = $options[array_search($selectedIndex, $choices)];

        if ($selectedOption === 'CREATE_NEW') {
            $newFolderName = $this->ask('Enter new folder name (will be created under Pages/):');
            $newFolderName = ucfirst($newFolderName);

            return "App\\Livewire\\Pages\\{$newFolderName}";
        }

        return $selectedOption;
    }
}
