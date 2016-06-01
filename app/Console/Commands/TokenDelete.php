<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use Storage;

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
        $token = $this->argument('token');
        if (Storage::disk('local')->has('/api/'.$token)) {
            if ($this->confirm('Are You Sure to Delete This Token?')) {
                Storage::disk('local')->delete('/api/'.$token);
                $this->info('Token Delete Success!');
            }
        } else {
            $this->error('This Token is Not Exist!');
        }
    }
}
