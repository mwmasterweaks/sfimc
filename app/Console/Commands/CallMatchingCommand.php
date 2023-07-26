<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CronJobs;

class CallMatchingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:matching';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Call Matching';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $CronJobs = new CronJobs();
        $temp = $CronJobs->doScheduledJobs();
        $this->info('command call success ' . $temp);
        return "command call success";
    }
}
