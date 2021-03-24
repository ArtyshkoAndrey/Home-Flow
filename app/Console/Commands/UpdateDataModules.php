<?php

namespace App\Console\Commands;

use App\Events\ModuleUpdate;
use App\Models\Module;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\Exceptions\DataTransferException;
use PhpMqtt\Client\Exceptions\RepositoryException;
use PhpMqtt\Client\Facades\MQTT;

class UpdateDataModules extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'modules:run';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

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
   * @return void
   */
  public function handle(): void
  {
    $mqtt = MQTT::connection();
    $modules = Module::all();
    echo 'Start MQTT subscribe';
    foreach ($modules as $module) {
      try {
        $mqtt->subscribe($module->mqtt, function (string $topic, string $message) use ($module) {
          Log::debug('Received QoS level 1 message on topic '. $topic. ' message: ' . $message);
          $module->data = $message;
          $module->save();
        }, 1);
      } catch (DataTransferException | RepositoryException $e) {
      }
    }
    $mqtt->loop(true);
  }
}
