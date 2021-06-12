<?php
declare(strict_types=1);
namespace App\Console\Commands;

use App\Events\ModuleUpdate;
use App\Models\Module;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\Exceptions\DataTransferException;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\Exceptions\ProtocolViolationException;
use PhpMqtt\Client\Exceptions\RepositoryException;
use PhpMqtt\Client\Facades\MQTT;
use PhpMqtt\Client\MqttClient;
use Symfony\Component\Console\Command\SignalableCommandInterface;

class UpdateDataModules extends Command  implements SignalableCommandInterface
{
  public MqttClient $mqtt;
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
    $this->mqtt = MQTT::connection();
  }

  /**
   * Get the list of signals handled by the command.
   *
   * @return array
   */
  public function getSubscribedSignals(): array
  {
    return [SIGINT, SIGTERM];
  }

  /**
   * Handle an incoming signal.
   *
   * @param  int  $signal
   * @return void
   */
  public function handleSignal(int $signal): void
  {
    if ($signal === SIGINT) {
      $this->mqtt->interrupt();
      return;
    }
  }

  /**
   * Execute the console command.
   *
   * @return void
   */
  public function handle(): void
  {
    try {
      $modules = Module::all();
      echo 'Start MQTT subscribe';
      foreach ($modules as $module) {
        try {
          $this->mqtt->subscribe($module->mqtt, function (string $topic, string $message) use ($module) {
            Log::debug('Received QoS level 1 message on topic ' . $topic . ' message: ' . $message);
            $module->data = $message;
            $module->save();
          }, 1);
        } catch (DataTransferException | RepositoryException $e) {
        }
      }

      $this->mqtt->loop(true);
      $this->mqtt->disconnect();
    } catch (MqttClientException $e) {
      // MqttClientException is the base exception of all exceptions in the library. Catching it will catch all MQTT related exceptions.
      echo 'Subscribing to a topic using QoS 0 failed. An exception occurred.';
    }
  }
}
