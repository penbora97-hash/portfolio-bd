<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateAdminPassword extends Command
{
    protected $signature = 'admin:generate-password';
    protected $description = 'Generate a strong password for admin';

    public function handle()
    {
        $password = Str::password(16, true, true, false, false);

        $this->info('🔐 Strong Password: ' . $password);
        $this->newLine();
        $this->warn('Copy this to ADMIN_PASSWORD in your .env file');

        return Command::SUCCESS;
    }
}
