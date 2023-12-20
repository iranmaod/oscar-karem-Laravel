<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendDueEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deal:dueemail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send installment Due email';

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
        return 0;
    }
}
