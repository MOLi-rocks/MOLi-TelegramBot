<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use MOLiBot\Services\MOLiBotApiTokenService;

class TokenDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:delete {token}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Specified API in MOLi Bot';

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
        $token = $this->argument('token');

        if ( $this->MOLiBotApiTokenService->checkTokenExist($token) ) {
            if ($this->confirm('Are You Sure to Delete This Token?')) {
                $this->MOLiBotApiTokenService->deleteToken($token);

                $this->info('Token Delete Success!');
            }
        } else {
            $this->error('This Token is Not Exist!');
        }
    }
}
