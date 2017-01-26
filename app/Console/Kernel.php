<?php

namespace App\Console;

use Illuminate\Console\Command;
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
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call(function () {

            $endPointCompute = 'http://10.0.0.101:61208/';
            $endPointProvider = 'http://104.236.82.72/';

            $client = new \GuzzleHttp\Client();
            $res = $client->get($endPointCompute . 'api/2/quicklook');
            $memPercent = json_decode($res->getBody())->mem;
            $cpuPercent = json_decode($res->getBody())->cpu;
            $storagePercent = json_decode($client->get($endPointCompute . 'api/2/fs')->getBody())[0]->percent;

//            \Log::info($memPercent);
//            \Log::info($cpuPercent);
//            \Log::info($storagePercent);
            //\Log::info(file_get_contents("http://10.0.0.100:15000/nova/nova-api.log"));

            $res = $client->post($endPointProvider . 'postPerformance',
                [
                    'form_params' => [
                        'client_id' => '2',
                        'client_cpu_usage' => $cpuPercent,
                        'client_memory_usage' => $memPercent,
                        'client_storage_usage' => $storagePercent
                    ]
                ]);

            $res = $client->post($endPointProvider . 'postLogFile',
                [
                    'multipart' => [
                        [
                            'name' => 'client_id',
                            'contents' => '2'
                        ],
                        [
                            'name'     => 'logFile',
                            'contents' => file_get_contents("http://10.0.0.100:15000/nova/nova-api.log"),
                            'filename' => 'nova-api.log'
                        ]
                    ]
                ]);
            //\Log::info($res->getBody());
        })->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
