<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use MOLiBot\Services\MOLiBotApiTokenService;

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
        $who = $this->ask('who will use this token?');

        $data = $this->MOLiBotApiTokenService->createToken($who);

        $this->info('Token for ' . $data->user . ' is ' . $data->token);

        return 0;
    }
}
