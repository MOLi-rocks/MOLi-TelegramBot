<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use MOLiBot\Models\MOLi_Bot_ApiToken;

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
     * @var MOLi_Bot_ApiToken
     */
    private $MOLi_Bot_API_TOKENModel;

    /**
     * Create a new command instance.
     *
     * @param MOLi_Bot_ApiToken $MOLi_Bot_API_TOKENModel
     *
     * @return void
     */
    public function __construct(MOLi_Bot_ApiToken $MOLi_Bot_API_TOKENModel)
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
        $token = $this->argument('token');
        if ($this->MOLi_Bot_API_TOKENModel->where('token', $token)->exists()) {
            if ($this->confirm('Are You Sure to Delete This Token?')) {
                $this->MOLi_Bot_API_TOKENModel->where('token', $token)->delete();
                $this->info('Token Delete Success!');
            }
        } else {
            $this->error('This Token is Not Exist!');
        }
    }
}
