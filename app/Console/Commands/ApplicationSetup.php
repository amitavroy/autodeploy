<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ApplicationSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'code:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will setup an application with the require folder structure.';

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
        $outout = shell_exec('~/.composer/vendor/bin/envoy run code-update');
        echo $outout;
    }
}
