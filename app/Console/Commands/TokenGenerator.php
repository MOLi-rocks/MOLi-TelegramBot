<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use MOLiBot\MOLi_Bot_API_TOKEN;
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
        $who = $this->ask('who will use this token?');
        $fp = md5(((float) date ( "YmdHis" ) + rand(100,999)).rand(1000,9999));
        MOLi_Bot_API_TOKEN::create(['token' => $fp, 'user' => $who]);
        $this->info('Token for ' . $who . ' is ' . $fp);
    }
}
