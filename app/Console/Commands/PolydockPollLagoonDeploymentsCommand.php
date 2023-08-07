<?php

namespace App\Console\Commands;

use App\Jobs\PolydockPollLagoonDeploymentsJob;
use Illuminate\Console\Command;

class PolydockPollLagoonDeploymentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'polydock:poll-lagoon-deployments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Polls lagoon for status updates of all running deployments';

    /** 
     * Execute the console command.
     */
    public function handle()
    {
        PolydockPollLagoonDeploymentsJob::dispatch();
        $this->info("Lagoon polling job dispatched");
    }
}
