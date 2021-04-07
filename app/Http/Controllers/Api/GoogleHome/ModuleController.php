<?php

namespace App\Http\Controllers\Api\GoogleHome;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Room;
use Illuminate\Http\JsonResponse;

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

}
