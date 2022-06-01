<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        /**
         * 要在linux 的定时任务写写上这个
          * * * * * php /home/vagrant/Code/larabbs/artisan schedule:run >> /dev/null 2>&1
         */
        $schedule->command('larabbs:calculate-active-user')->hourly();              #活跃用户
        $schedule->command('larabbs:sync-user-actived-at')->dailyAt('00:00');  #用户最后登录时间
        $schedule->command('sanctum:prune-expired --hours=24')->daily();            #Sanctum 令牌永远不会过期, 通过命令的方式过期
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
