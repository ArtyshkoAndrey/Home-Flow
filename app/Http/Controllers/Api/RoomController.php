<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoomController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return JsonResponse
   */
  public function index(): JsonResponse
  {
    $rooms = Room::all();
    return response()->json(['rooms' => $rooms]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function store(Request $request): JsonResponse
  {
    $request->validate([
      'name' => 'required|string|unique:rooms|min:1|max:255',
      'index' => 'required|string|unique:rooms|min:1|max:255'
    ]);

    $room = new Room($request->all());
    $room->save();

    return response()->json($room);
  }

  /**
   * Display the specified resource.
   *
   * @param int $id
   * @return JsonResponse
   */
  public function show(int $id): JsonResponse
  {
    $room = Room::findOrFail($id);

    return response()->json($room);
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
    $request->validate([
      'name' => 'required|string|unique:rooms,'. $id . '|min:1|max:255',
      'index' => 'required|string|unique:rooms,'. $id . '|min:1|max:255'
    ]);

    $room = Room::findOrFail($id);

    $room->update($request->all());
    $room->save();

    return response()->json($room);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return JsonResponse
   */
  public function destroy(int $id): JsonResponse
  {
    $room = Room::findOrFail($id);
    $room->delete();

    return response()->json('success');
  }
}
