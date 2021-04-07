<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Room;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
      'name'          => 'required|string',
      'google_index'  => 'required|string',
      'ico'           => 'required|string',
      'mqtt'          => 'required|string',
      'room'          => 'required|exists:rooms,id',
      'type'          => 'required|exists:types,id',
    ]);

    $data = $request->all();
    $module = new Module($data);
    $module->room()
      ->associate($data['room']);
    $module->type()
      ->associate($data['type']);
    $module->save();
    return response()->json(['module' => $module]);
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
      $module = Module::whereId($id)
        ->firstOrFail();
      return response()->json(['module' => $module]);
    } catch (ModelNotFoundException $e) {
      return response()->json(['error' => $e->getMessage()]);
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param int $id
   * @return Response
   */
  public function update(Request $request, int $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return Response
   */
  public function destroy(int $id)
  {
    //
  }
}
