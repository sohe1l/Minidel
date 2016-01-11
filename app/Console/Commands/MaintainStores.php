<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MaintainStores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'minidel:maintainstores';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check working status on the stores.';

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
     * @return mixed
     */
    public function handle()
    {
        $stores = \App\Store::isNotOpen()->statusWorkingIsExpired()->update(['status_working'=>'open', 'status_working_expire'=>null]);
    }
}
