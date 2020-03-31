<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        Command\GenerateInvoice::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->call(function () {
            $data = [
                'title' => 'Test One Minute Spam Mail',
                'content' => 'Spam main test. ',
                'link' => 'http://www.google.com/',
                'warning' => 'Link will expired in 1 day'
            ];
            Mail::send('page.auth.email', $data, function($message) {
                $message->to('crossoverandscore@gmail.com', 'brian ruchiadi')->subject('Hi, ' . 'brian ruchiadi');
            });
        })->everyMinute();
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
