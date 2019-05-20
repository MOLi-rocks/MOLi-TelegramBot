<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use MOLiBot\Services\MOLiBotApiTokenService;

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
     * @var MOLiBotApiTokenService
     */
    private $MOLiBotApiTokenService;

    /**
     * Create a new command instance.
     * 
     * @param MOLiBotApiTokenService $MOLiBotApiTokenService
     *
     * @return void
     */
    public function __construct(MOLiBotApiTokenService $MOLiBotApiTokenService)
    {
        parent::__construct();

        $this->MOLiBotApiTokenService = $MOLiBotApiTokenService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tokens = $this->MOLiBotApiTokenService->listToken();
        $headers = ['User', 'Token', 'created_at'];
        $this->table($headers, $tokens);
    }
}
