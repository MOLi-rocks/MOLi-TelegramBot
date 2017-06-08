<?php

namespace MOLiBot\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Telegram;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
        Commands\TokenGenerator::class,
        Commands\TokenList::class,
        Commands\TokenDelete::class,
        Commands\NCNU_RSS::class,
        Commands\MOLiDay_Events::class,
        Commands\GetFuelPriceGap::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();

        $schedule->command('rss:check')
                 ->everyTenMinutes()->withoutOverlapping();
        
        $schedule->command('kktix:check')
                 ->everyTenMinutes()->withoutOverlapping();

        $schedule->command('fuelprice:checkgap')
                 ->weekly()->sundays()->at('12:05')->withoutOverlapping();
    }
}
