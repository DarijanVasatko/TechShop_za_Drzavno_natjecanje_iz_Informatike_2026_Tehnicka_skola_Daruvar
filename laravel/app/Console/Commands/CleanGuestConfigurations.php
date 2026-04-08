<?php

namespace App\Console\Commands;

use App\Models\PcConfiguration;
use Illuminate\Console\Command;

class CleanGuestConfigurations extends Command
{
    protected $signature   = 'pcbuilder:clean-guest {--days=7 : Briši konfiguracije starije od N dana}';
    protected $description = 'Briše PC konfiguracije gosta starije od zadanog broja dana (default: 7)';

    public function handle(): int
    {
        $days = (int) $this->option('days');

        $deleted = PcConfiguration::whereNull('user_id')
            ->where('created_at', '<', now()->subDays($days))
            ->delete();

        $this->info("Obrisano {$deleted} gostujućih PC konfiguracija starijih od {$days} dana.");

        return Command::SUCCESS;
    }
}
