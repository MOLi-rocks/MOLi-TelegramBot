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
     * @var MOLi_Bot_API_TOKEN
     */
    private $MOLi_Bot_API_TOKENModel;

    /**
     * Create a new command instance.
     * 
     * @param MOLi_Bot_API_TOKEN $MOLi_Bot_API_TOKENModel
     *
     * @return void
     */
    public function __construct(MOLi_Bot_API_TOKEN $MOLi_Bot_API_TOKENModel)
    {
        parent::__construct();

        $this->MOLi_Bot_API_TOKENModel = $MOLi_Bot_API_TOKENModel;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tokens = $this->MOLi_Bot_API_TOKENModel->all(['user', 'token', 'created_at'])->toArray();
        $headers = ['User', 'Token', 'created_at'];
        $this->table($headers, $tokens);
    }
}
