<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [

        '\App\Console\Commands\loanCronJob',
//        '\App\Console\Commands\cronAdjustLeaves',
//        '\App\Console\Commands\leavePolicy',
//        '\App\Console\Commands\cronEmail',
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('notify:loan')
            ->everyMinute();
        $schedule->command('notify:monthlyAttendance')
            ->monthlyOn(1, '00:00');
//        $schedule->command('notify:AdjustLeaves')
//            ->everyMinute();

//        $schedule->command('notify:leavePolicy')
//            ->cron('0 0 1 1 *');
//        $schedule->command('notify:leave')
//            ->everyMinute();
//        $schedule->command('notify:leavePolicy')
//            ->cron('0 0 1 1 *');

    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}