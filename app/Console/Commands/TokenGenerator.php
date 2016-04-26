<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use Storage;
use Hash;

class TokenGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate New Token for MOLi Bot API';

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
        $fp = md5(((float) date ( "YmdHis" ) + rand(100,999)).rand(1000,9999));
        Storage::disk('local')->put('/api/'.$fp, '');
    }
}
