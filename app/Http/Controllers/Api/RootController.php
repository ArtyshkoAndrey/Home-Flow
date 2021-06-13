<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\JsonResponse;

class RootController extends Controller
{

  public function status(): JsonResponse
  {
    $countModules = Module::count();

    return response()->json([
      'countModules' => $countModules
    ]);
  }
}
