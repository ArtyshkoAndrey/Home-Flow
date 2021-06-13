<?php
declare(strict_types=1);

namespace App\Console\Commands\Local;

use App\Models\Module;
use Illuminate\Console\Command;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;
use App\MQTT\SimpleLogger;
use App\MQTT\MQTTHelp;
use Psr\Log\LogLevel;

class UpdateDataModules extends Command
{

  /**
   * Массив ссылок которые активные в прослуживании
   *
   * @var array
   */
  public array $links = [];

  /**
   * Через сколько секунд обновляем данные с бд
   *
   * @var int
   */
  public int $elSeconds = 5;


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
  protected $description = 'Запуск потока для MQTT';

  /**
   * Execute the console command.
   *
   * @return void
   */
  public function handle(): void
  {
    $logger = new SimpleLogger(LogLevel::INFO);

    $client = new MQTTHelp(
      config('mqtt-client.connections.default.host'),
      (int) config('mqtt-client.connections.default.port'),
      config('mqtt-client.connections.default.client_id'),
    );

    $connectionSettings = (new ConnectionSettings())
      ->setUsername(config('mqtt-client.connections.default.connection_settings.auth.username'))
      ->setPassword(config('mqtt-client.connections.default.connection_settings.auth.password'));

    $client->connect(
      $connectionSettings,
      true
    );

    $client->registerMessageReceivedEventHandler( function (MqttClient $client, string $topic, string $message, int $qualityOfService, bool $retained) use ($logger) {
      $logger->info('Received message on topic [{topic}] with QoS {qos}: {message}', [
        'topic' => $topic,
        'message' => $message,
        'qos' => $qualityOfService,
      ]);
      dump($message);
      Log::debug('Received QoS level 1 message on topic ' . $topic . ' message: ' . $message);
      $module = Module::where('mqtt', $topic)->first();
      $module->data = $message;
      $module->save();
//      $client->interrupt();
    });

    $loopStartedAt = microtime(true);
    $elapsedTime = $loopStartedAt;
    $logger->info('Цикл запущен');
    while (true) {
//      $logger->info('Topic Module [{topic}] ', [
//        'topic' => microtime(true) ,
//      ]);
//
//      $client->subscribe('foo/bar/baz');
//
//      $client->unsubscribe('foo/#');

//      Алгорит обновления слушателей
      if (microtime(true) - $elapsedTime >= $this->elSeconds) {
        $logger->info('Запущен алгоритм обновления модулей');
//        берём свежие данные из бд
        $modules = Module::all();
//        Вывод в кончоль информацию о нынешнем времени
        $logger->info('Сейчас время в секундах [{time}] ', [
          'time' =>  (int) microtime(true),
        ]);

        $links = [];
//        Перебираем все модули, для прослушивания
        foreach ($modules as $module) {
          $links[] = $module->mqtt;
          $logger->info('Topic Module [{topic}] ', [
            'topic' => $module->mqtt,
          ]);
        }
        $diff = array_diff($links, $this->links);

        if (count($diff) > 0) {
          $logger->info('Расхождения массива');
          dump($links);
          foreach ($this->links as $link) {
//            $client->unsubscribe($link);
          }
          foreach ($links as $link) {
            $client->subscribe($link);
          }
//          break;
        } else {
          $logger->info('Ссылки не изменились');
        }
        $this->links = $links;
        $elapsedTime = microtime(true);
        $logger->info('-----------------------------------------------------');
      }

//      Если ссылок для слушания больше нуля, то запускаем слушателя
      if (count($this->links) > 0) {
        $logger->info('Начали слушать');
        $client->loop(false, true, 10);
        $logger->info('Закончели слушать');
        $logger->info('-----------------------------------------------------');
      }
    }
//    $client->publish('foo/bar/baz', 'Hello world!', MqttClient::QOS_AT_MOST_ONCE);
    $client->disconnect();
  }

  public function sub ()
  {
    $logger = new SimpleLogger(LogLevel::INFO);

  }

//  public function handle(): void
//  {
//
//    pcntl_async_signals(true);
//
//    try {
//      $mqtt = MQTT::connection();
//      pcntl_signal(SIGINT, static function (int $signal, $info) use ($mqtt) {
//        $mqtt->interrupt();
//      });
//      $modules = Module::all();
//      echo 'Start MQTT subscribe';
////      while(true) {
//        $mqtt->subscribe('/test', function (string $topic, string $message) {
//          Log::debug('Received QoS level 1 message on topic ' . $topic . ' message: ' . $message);
//        }, 1);
////      }
//      foreach ($modules as $module) {
//        try {
//          $mqtt->subscribe($module->mqtt, function (string $topic, string $message) use ($module) {
//            Log::debug('Received QoS level 1 message on topic ' . $topic . ' message: ' . $message);
//            $module->data = $message;
//            $module->save();
//          }, 1);
//        } catch (DataTransferException | RepositoryException $e) {
//
//        }
//      }
//
//      $mqtt->loop(true, true, 1);
//      $mqtt->disconnect();
//    } catch (MqttClientException $e) {
//      // MqttClientException is the base exception of all exceptions in the library. Catching it will catch all MQTT related exceptions.
//      echo 'Subscribing to a topic using QoS 0 failed. An exception occurred.';
//    }
//  }
}
