<?php
declare(strict_types=1);

namespace App\Console\Commands\Local;

use App\Models\Module;
use App\MQTT\MqttClient;
use App\MQTT\SimpleLogger;
use Closure;
use Illuminate\Console\Command;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\Exceptions\MqttClientException;
use Psr\Log\LogLevel;

class UpdateDataModules extends Command
{
  /**
   * Обьект для вывода информации в консоль
   *
   * @var SimpleLogger
   */
  public SimpleLogger $logger;

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
    $this->logger = new SimpleLogger(LogLevel::INFO);

    try {
//    Создание клиента для MQTT
      $client = new MqttClient(
        config('mqtt-client.connections.default.host'),
        (int)config('mqtt-client.connections.default.port'),
        config('mqtt-client.connections.default.client_id'),
      );

//    Данные авторизации для MQTT
      $connectionSettings = (new ConnectionSettings())
        ->setUsername(config('mqtt-client.connections.default.connection_settings.auth.username'))
        ->setPassword(config('mqtt-client.connections.default.connection_settings.auth.password'));

//    Подключение к MQTT
      $client->connect(
        $connectionSettings,
        true
      );

      $client->registerMessageReceivedEventHandler($this->receivedEventHandler());

      $loopStartedAt = microtime(true);
      $elapsedTime = $loopStartedAt;
      $this->logger->info('Цикл запущен');

      while (true) {

//      Алгорит обновления слушателей
        if (microtime(true) - $elapsedTime >= $this->elSeconds) {
          $this->logger->info('Запущен алгоритм обновления модулей');
//        берём свежие данные из бд
          $modules = Module::all();
//        Вывод в кончоль информацию о нынешнем времени
          $this->logger->info('Сейчас время в секундах [{time}] ', [
            'time' => (int)microtime(true),
          ]);

          $links = [];
//        Перебираем все модули, для прослушивания
          foreach ($modules as $module) {
            $links[] = $module->mqtt;
            $this->logger->info('Topic Module [{topic}] ', [
              'topic' => $module->mqtt,
            ]);
          }
          $diff = array_diff($links, $this->links);

          if (count($diff) > 0) {
            $this->logger->info('Расхождения массива');
            dump($links);
            foreach ($this->links as $link) {
              $client->unsubscribe($link);
            }
            foreach ($links as $link) {
              $client->subscribe($link);
            }
//          break;
          } else {
            $this->logger->info('Ссылки не изменились');
          }
          $this->links = $links;
          $elapsedTime = microtime(true);
          $this->logger->info('-----------------------------------------------------');
        }

//      Если ссылок для слушания больше нуля, то запускаем слушателя
        if (count($this->links) > 0) {
          $this->logger->info('Алгоритм начал слушать в течении [{time}] с.', [
            'time' => $this->elSeconds
          ]);
          $client->loop(false, true, $this->elSeconds);
          $this->logger->info('Закончели слушать');
          $this->logger->info('-----------------------------------------------------');
        }
      }

      $client->disconnect();
    } catch (MqttClientException $e) {
      $this->logger->error('Ошибка к подключение MQTT. Возможно ошибка в слушателе или в данных для входа.', ['exception' => $e]);
    }
  }

  /**
   * Метод для обработки калбека при получении сообщения
   *
   * @return Closure
   */
  private function receivedEventHandler(): Closure
  {
    return function (MqttClient $client, string $topic, string $message, int $qualityOfService, bool $retained) {
      $this->logger->info('В топике [{topic}] с QOS {qos} было получено сообщение: {message}', [
        'topic' => $topic,
        'message' => $message,
        'qos' => $qualityOfService,
      ]);
      $module = Module::where('mqtt', $topic)->first();
      if ($module) {
        $module->data = $message;
        $module->save();
      } else {
        $this->logger->info('Ошибка в нахождения модуля');
      }
    };
  }
}
