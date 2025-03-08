<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TranslateJsonCommand extends Command
{
    protected $signature = 'lang:translate {target} {source=en} {--chunk=100} {--api=deepl}';

    protected $description = 'Translate JSON language files using translation API';

    public function handle()
    {
        $source = $this->argument('source');
        $target = $this->argument('target');
        $chunkSize = $this->option('chunk');
        $api = $this->option('api');

        $sourcePath = base_path("lang/{$source}.json");
        $targetPath = base_path("lang/{$target}.json");

        if (! file_exists($sourcePath)) {
            $this->error("Source file {$sourcePath} not found");

            return 1;
        }

        // Load source translations
        $sourceTranslations = json_decode(file_get_contents($sourcePath), true);

        // Load existing target translations if they exist
        $targetTranslations = [];
        if (file_exists($targetPath)) {
            $targetTranslations = json_decode(file_get_contents($targetPath), true) ?: [];
        }

        // Find untranslated strings
        $untranslated = [];
        foreach ($sourceTranslations as $key => $value) {
            // Skip already translated strings or where key = value in source (placeholders)
            if (! isset($targetTranslations[$key]) || $targetTranslations[$key] === $key) {
                $untranslated[$key] = $value;
            }
        }

        if (empty($untranslated)) {
            $this->info('All strings are already translated!');

            return 0;
        }

        $this->info('Found '.count($untranslated).' strings to translate');

        // Process in chunks to avoid API limits
        $chunks = array_chunk($untranslated, $chunkSize, true);
        $bar = $this->output->createProgressBar(count($chunks));

        foreach ($chunks as $chunk) {
            // Translate the chunk based on selected API
            if ($api === 'deepl') {
                $translated = $this->translateWithDeepL($chunk, $target, $source);
            } else {
                $this->error("API not supported: {$api}");

                return 1;
            }

            // Merge translations
            $targetTranslations = array_merge($targetTranslations, $translated);

            // Save after each chunk in case of errors
            file_put_contents(
                $targetPath,
                json_encode($targetTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            );

            $bar->advance();

            // Small delay to respect API rate limits
            sleep(1);
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Translation completed and saved to {$targetPath}");

        return 0;
    }

    protected function translateWithDeepL(array $strings, string $targetLang, string $sourceLang = 'EN')
    {
        // Replace with your DeepL API key
        $apiKey = env('DEEPL_API_KEY');

        if (empty($apiKey)) {
            $this->error('DeepL API key not found. Add DEEPL_API_KEY to your .env file');
            exit(1);
        }

        $result = [];
        $values = array_values($strings);
        $keys = array_keys($strings);

        $response = Http::withHeaders([
            'Authorization' => "DeepL-Auth-Key {$apiKey}",
            'Content-Type' => 'application/json',
        ])->post('https://api.deepl.com/v2/translate', [
            'text' => $values,
            'target_lang' => strtoupper($targetLang),
            'source_lang' => strtoupper($sourceLang),
        ]);

        if ($response->successful()) {
            $translations = $response->json()['translations'];

            foreach ($keys as $index => $key) {
                $result[$key] = $translations[$index]['text'] ?? $strings[$key];
            }

            return $result;
        }

        $this->error('DeepL API error: '.$response->body());

        return $strings; // Return original strings if translation fails
    }
}
