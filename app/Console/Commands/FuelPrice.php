<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

class FuelPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fuelprice:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get newest fuel price from http://data.gov.tw/node/6339';

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
        //
    }
}
