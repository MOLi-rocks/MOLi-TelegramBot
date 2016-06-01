<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use Storage;

class TokenList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List All Token for MOLi Bot API';

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
        $result = array();
        $tokens = Storage::disk('local')->files('/api/');
        foreach ($tokens as $token) {
            $puretoken = explode('/', $token);
            $who = Storage::disk('local')->get($token);
            array_push($result, array($who, $puretoken[1]));
        }
        $headers = ['User', 'Token'];
        $this->table($headers, $result);
    }
}
