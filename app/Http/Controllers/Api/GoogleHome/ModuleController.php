<?php

namespace App\Http\Controllers\Api\GoogleHome;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\MqttClient;

/**
 * Class ModuleController
 * @package App\Http\Controllers\Api\GoogleHome
 */
class ModuleController extends Controller
{

  /**
   * Return all Rooms and modules
   *
   * @return JsonResponse
   */
  public function index(): JsonResponse
  {
    $rooms = Room::with(['modules', 'modules.type', 'modules.type.google_type', 'modules.type.google_traits'])->get();
    return response()->json(['rooms' => $rooms]);
  }

  /**
   * Return one module
   *
   * @param int $id
   * @return JsonResponse
   */
  public function get(int $id): JsonResponse
  {
    $module = Module::with(['type', 'type.google_type', 'type.google_traits'])->find($id);
    return response()->json(['module' => $module]);
  }

  /**
   * Set Data
   *
   * @param Request $request
   * @param int $id
   * @return JsonResponse
   */
  public function set(Request $request, int $id): JsonResponse
  {
    $module = Module::find($id);
    if ($request->has('data')) {
      $module->data = $request->get('data');
      $module->save();

      try {
        // Create a new instance of an MQTT client and configure it to use the shared broker host and port.
        $client = new MqttClient(
          config('mqtt-client.connections.default.host'),
          (int)config('mqtt-client.connections.default.port'),
          config('mqtt-client.connections.default.client_id') . "-rand",
        );

//    Данные авторизации для MQTT
        $connectionSettings = (new ConnectionSettings())
          ->setUsername(config('mqtt-client.connections.default.connection_settings.auth.username'))
          ->setPassword(config('mqtt-client.connections.default.connection_settings.auth.password'));

//    Подключение к MQTT
        $client->connect(
          $connectionSettings,
          false
        );

        // Publish the message 'Hello world!' on the topic 'foo/bar/baz' using QoS 1.
        $client->publish($module->mqtt, $request->get('data'), MqttClient::QOS_AT_MOST_ONCE);

        // Since QoS 1 requires the publisher to await confirmation and resend the message if no confirmation is received,
        // we need to start the client loop which takes care of that. By passing `true` as second parameter,
        // we allow the loop to exit as soon as all confirmations have been received.

        // Gracefully terminate the connection to the broker.
        $client->disconnect();
        return response()->json(['status' => true]);
      } catch (MqttClientException $e) {
        // MqttClientException is the base exception of all exceptions in the library. Catching it will catch all MQTT related exceptions.
        return response()->json(['status' => false]);
      }
    }
    return response()->json(['status' => false]);
  }

}
