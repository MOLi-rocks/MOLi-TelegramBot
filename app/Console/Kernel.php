<?php

namespace MOLiBot\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('inspire')->hourly();

        $schedule->command('ncnu:rss-check')
                 ->everyTenMinutes()->withoutOverlapping();
        
        $schedule->command('MOLi:kktix-event-check')
                 ->everyTenMinutes()->withoutOverlapping();

        $schedule->command('MOLi:blog-article-check')
                 ->everyTenMinutes()->withoutOverlapping();

        $schedule->command('fuelprice:checkgap')
                 ->weekly()->sundays()->at('12:05')->withoutOverlapping();

        $schedule->command('ncdr:rss-check')
            ->everyTenMinutes()->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
