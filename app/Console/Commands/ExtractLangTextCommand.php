<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ExtractLangTextCommand extends Command
{
    // Define the command signature to accept a locale argument and an optional path
    protected $signature = 'lang:extract {locale} {path?} {--merge : Merge with existing translations}';

    protected $description = 'Extract all text within the __() helper and output to the /lang/{locale}.json file or a custom path';

    // Execute the command
    public function handle()
    {
        // Get the locale and optional output path from the command arguments
        $locale = $this->argument('locale');
        $path = $this->argument('path') ?: base_path('lang'); // Default to base_path('/lang') if no path is provided
        $shouldMerge = $this->option('merge');

        // Ensure the directory exists
        if (! is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $outputFile = $path."/$locale.json";
        $existingTranslations = [];

        // Handle existing file
        if (file_exists($outputFile)) {
            // Load existing translations if merging
            if ($shouldMerge) {
                $existingTranslations = json_decode(file_get_contents($outputFile), true) ?: [];
                $this->components->info('Loaded existing translations for merging.');
            } else {
                // Confirm before overwriting
                if (! $this->confirm("$outputFile already exists. Are you sure you want to overwrite it?")) {
                    return;
                }
            }
        }

        // Find all files in app, routes, and resources/views directories
        $directories = [
            base_path('app'),
            base_path('routes'),
            base_path('resources/views'),
        ];

        $translations = [];

        foreach ($directories as $directory) {
            if (! is_dir($directory)) {
                $this->components->warn("Directory not found: $directory");

                continue;
            }

            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
            foreach ($files as $file) {
                // Properly check for PHP and Blade files
                if ($file->isFile() &&
                    ($file->getExtension() === 'php' || str_ends_with($file->getFilename(), '.blade.php'))) {
                    $content = file_get_contents($file->getPathname());

                    // Improved regex to find more instances of __()
                    preg_match_all("/__\(\s*[\'\"](.*?)(?<!\\\\)[\'\"]/", $content, $matches);

                    // Store the results
                    foreach ($matches[1] as $key) {
                        if (! isset($translations[$key])) {
                            // If merging, preserve existing translations
                            $translations[$key] = $existingTranslations[$key] ?? $key;
                        }
                    }
                }
            }
        }

        // If merging, combine with existing translations
        if ($shouldMerge && ! empty($existingTranslations)) {
            $translations = array_merge($existingTranslations, $translations);
        }

        ksort($translations);

        // Write translations to a JSON file in the target directory
        file_put_contents($outputFile, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        $this->components->info("Translations extracted successfully and saved to $outputFile");
        $this->components->info('Total translations: '.count($translations));
    }
}
