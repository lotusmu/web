<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ConvertVoltBatch extends Command
{
    protected $signature = 'theme:convert-volt-batch {directory} {--theme=default} {--dry-run}';

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
}
