<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use MOLiBot\MOLi_Bot_API_TOKEN;

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
        $tokens = MOLi_Bot_API_TOKEN::all(['user', 'token', 'created_at'])->toArray();
        $headers = ['User', 'Token', 'created_at'];
        $this->table($headers, $tokens);
    }
}
