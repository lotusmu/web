<?php

namespace App\Console\Commands;

use App\Models\User\Member;
use App\Models\User\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class ImportUsersCommand extends Command
{
    protected $signature = 'import:users {file : The CSV file to import}';

    protected $description = 'Import users from a CSV file';

    public function handle()
    {
        $file = $this->argument('file');

        $csv = Reader::createFromPath($file, 'r');
        $csv->setHeaderOffset(0);

        $totalRows = $csv->count();
        $importedCount = 0;
        $skippedCount = 0;

        $this->output->progressStart($totalRows);

        foreach ($csv as $record) {
            if (! isset($record['memb___id']) || ! isset($record['mail_addr']) || ! isset($record['memb__pwd'])) {
                $this->warn('Skipping row due to missing required fields: '.implode(', ', $record));
                $skippedCount++;
                $this->output->progressAdvance();

                continue;
            }

            $existingUser = User::where('name', $record['memb___id'])
                ->orWhere('email', $record['mail_addr'])
                ->first();

            $existingMember = Member::where('memb___id', $record['memb___id'])
                ->orWhere('mail_addr', $record['mail_addr'])
                ->first();

            if ($existingUser || $existingMember) {
                $this->warn("Skipping duplicate: {$record['memb___id']} / {$record['mail_addr']}");
                $skippedCount++;
                $this->output->progressAdvance();

                continue;
            }

            DB::transaction(function () use ($record) {
                User::create([
                    'name' => $record['memb___id'],
                    'email' => $record['mail_addr'],
                    'password' => $record['memb__pwd'],
                ]);
            });

            $importedCount++;
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
        $this->info("Import completed. Imported: $importedCount, Skipped: $skippedCount");
    }
}
