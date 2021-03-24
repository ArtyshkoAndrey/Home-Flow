<?php

  namespace App\Http\Controllers\Api;

  use App\Http\Controllers\Controller;
  use App\Models\GoogleTrait;
  use App\Models\GoogleType;
  use Illuminate\Http\JsonResponse;

  class GoogleController extends Controller
  {
    /**
     * Get Google APi Types
     *
     * @return JsonResponse
     */
    public function types(): JsonResponse
    {
      $types = GoogleType::all();

      return response()->json(['types' => $types]);
    }

    /**
     * Get Google APi Traits
     *
     * @return JsonResponse
     */
    public function traits(): JsonResponse
    {
      $traits = GoogleTrait::all();

      return response()->json(['traits' => $traits]);
    }
  }
