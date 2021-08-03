<?php

namespace App\Console;

use App\Jobs\UpdateDataModules;
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
   * @param Schedule $schedule
   * @return void
   */
  protected function schedule(Schedule $schedule)
  {
    // $schedule->command('inspire')->hourly();
    $schedule->command('telescope:prune')->everyMinute();
//    $schedule->job(new UpdateDataModules())->everyMinute();
  }

  /**
   * Register the commands for the application.
   *
   * @return void
   */
  protected function commands()
  {
    $paths = [
      __DIR__ . '/Commands'
    ];

    if(app()->environment('local')) {
      $paths[] = __DIR__ . '/Local';
    }

    $this->load($paths);

    require base_path('routes/console.php');
  }
}
