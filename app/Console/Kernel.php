<?php

namespace App\Console;
use App\Console\Commands\ProductStock;
use App\Console\Commands\SMSAStatus;
use App\Console\Commands\StoreOrders;
use App\Console\Commands\WooOrders;
use App\Console\Commands\CancelOrder;
use App\Console\Commands\RefundOrder;
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
        WooOrders::class,
        StoreOrders::class,
        ProductStock::class,
        CancelOrder::class,
        RefundOrder::class,
        SMSAStatus::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('product:stock')->everyFiveMinutes();
        $schedule->command('store:orders')->everyFiveMinutes();
        $schedule->command('cancel:orders')->everyFiveMinutes();
        $schedule->command('woo:orders')->everyFiveMinutes();
        $schedule->command('refund:order')->everyFiveMinutes();
        $schedule->command('SMSA:status')->everyFiveMinutes();
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
