<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Room;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\MqttClient;

class ModuleController extends Controller
{
  /**
   * Display a listing of Module in Roo ms.
   *
   * @return JsonResponse
   */
  public function index(): JsonResponse
  {
    $rooms = Room::with(['modules', 'modules.type', 'modules.type.google_type'])->get();
    return response()->json(['rooms' => $rooms]);
  }

  /**
   * Store a newly created Module.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function store(Request $request): JsonResponse
  {
    $request->validate([
      'name' => 'required|string',
      'google_index' => 'required|string',
      'ico' => 'required|string|min:1',
      'mqtt' => 'required|string',
      'room' => 'required|exists:rooms,id',
      'type' => 'required|exists:types,id',
    ]);

    $data = $request->all();
    $module = new Module($data);
    $module->room()
      ->associate($data['room']);
    $module->type()
      ->associate($data['type']);
    $module->save();
    return response()->json(['module' => $module]);
//    return response()->json(['data' => $data]);
  }

  /**
   * Display Module.
   *
   * @param int $id
   * @return JsonResponse
   */
  public function show(int $id): JsonResponse
  {
    try {
      $module = Module::findOrFail($id);
      return response()->json(['module' => $module]);
    } catch (ModelNotFoundException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param int $id
   * @return JsonResponse
   */
  public function update(Request $request, int $id): JsonResponse
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

//    ???????????? ?????????????????????? ?????? MQTT
        $connectionSettings = (new ConnectionSettings())
          ->setUsername(config('mqtt-client.connections.default.connection_settings.auth.username'))
          ->setPassword(config('mqtt-client.connections.default.connection_settings.auth.password'));

//    ?????????????????????? ?? MQTT
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
      } catch (MqttClientException $e) {
        // MqttClientException is the base exception of all exceptions in the library. Catching it will catch all MQTT related exceptions.
        return response()->json($e->getMessage(), 500);
      }
    } else {
      $request->validate([
        'name' => 'required|string',
        'google_index' => 'required|string',
        'ico' => 'required|string|min:1',
        'mqtt' => 'required|string',
        'room' => 'required|exists:rooms,id',
        'type' => 'required|exists:types,id',
      ]);

      $data = $request->except(['data']);
      if ($module->type->type !== 'switch' && $module->type->type !== 'light') {
        $module->data = null;
      }
      $module->room()
        ->associate($data['room']);
      $module->type()
        ->associate($data['type']);
      $module->save();
    }

    return response()->json(['module' => $module]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return JsonResponse
   */
  public function destroy(int $id): JsonResponse
  {
    $module = Module::findOrFail($id);
    $module->delete();

    return response()->json('success');
  }
}
