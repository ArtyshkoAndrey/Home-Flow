<?php

namespace App\Jobs;

use App\Events\ModuleUpdate;
use App\Models\Module;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\Exceptions\DataTransferException;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\Exceptions\RepositoryException;
use PhpMqtt\Client\Facades\MQTT;

class UpdateDataModules implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $modules;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->modules = Module::all();
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle(): void
  {
    $mqtt = MQTT::connection();
    foreach ($this->modules as $module) {
      try {
        $mqtt->subscribe($module->mqtt, function (string $topic, string $message) use ($module) {
          Log::debug('Received QoS level 1 message on topic '. $topic. ' message: ' . $message);
          $module->data = $message;
          $module->save();
          event(new ModuleUpdate($module));
        }, 1);
      } catch (DataTransferException | RepositoryException $e) {
      }
    }
    $mqtt->loop(true);
  }
}
