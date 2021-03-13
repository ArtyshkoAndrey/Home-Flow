<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class Handler extends ExceptionHandler
{
  /**
   * A list of the exception types that are not reported.
   *
   * @var array
   */
  protected $dontReport = [
    //
  ];

  /**
   * A list of the inputs that are never flashed for validation exceptions.
   *
   * @var array
   */
  protected $dontFlash = [
    'password',
    'password_confirmation',
  ];

  /**
   * Convert an authentication exception into a response.
   *
   * @param Request $request
   * @param AuthenticationException $exception
   * @return JsonResponse|RedirectResponse
   */
  protected function unauthenticated($request, AuthenticationException $exception)
  {
    return $request->expectsJson()
      ? response()->json(['message' => $exception->getMessage()], 401)
      : redirect()->guest(url('/login'));
  }
}
