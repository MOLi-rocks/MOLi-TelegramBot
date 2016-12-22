<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

class NCNU_StaffContactInformation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contactInfo:search {name : 要搜尋的員工姓名關鍵字}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search NCNU Staff Contact Information';

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
        //
    }
}
