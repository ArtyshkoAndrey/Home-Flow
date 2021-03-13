<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\EmailTakenException;
use App\Http\Controllers\Controller;
use App\Models\OAuthProvider;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
  use AuthenticatesUsers;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    config([
      'services.github.redirect' => route('oauth.callback', 'github'),
    ]);
  }

  /**
   * Redirect the user to the provider authentication page.
   *
   * @param string $provider
   * @return array
   */
  public function redirect(string $provider): array
  {
    return [
      'url' => Socialite::driver($provider)->stateless()->redirect()->getTargetUrl(),
    ];
  }

  /**
   * Obtain the user information from the provider.
   *
   * @param string $provider
   * @return Application|Factory|View
   * @throws EmailTakenException
   */
  public function handleCallback(string $provider)
  {
    $user = Socialite::driver($provider)->stateless()->user();
    $user = $this->findOrCreateUser($provider, $user);

    $this->guard()->setToken(
      $token = $this->guard()->login($user)
    );

    return view('oauth/callback', [
      'token' => $token,
      'token_type' => 'bearer',
      'expires_in' => $this->guard()->getPayload()->get('exp') - time(),
    ]);
  }

  /**
   * @param string $provider
   * @param \Laravel\Socialite\Contracts\User $user
   * @return User
   * @throws EmailTakenException
   */
  protected function findOrCreateUser(string $provider, \Laravel\Socialite\Contracts\User $user): User
  {
    $oauthProvider = OAuthProvider::where('provider', $provider)
      ->where('provider_user_id', $user->getId())
      ->first();

    if ($oauthProvider) {
      $oauthProvider->update([
        'access_token' => $user->token,
        'refresh_token' => $user->refreshToken,
      ]);

      return $oauthProvider->user;
    }

    if (User::where('email', $user->getEmail())->exists()) {
      throw new EmailTakenException;
    }

    return $this->createUser($provider, $user);
  }

  /**
   * @param string $provider
   * @param \Laravel\Socialite\Contracts\User $sUser
   * @return User
   */
  protected function createUser(string $provider, \Laravel\Socialite\Contracts\User $sUser): User
  {
    $user = User::create([
      'name' => $sUser->getName(),
      'email' => $sUser->getEmail(),
      'email_verified_at' => now(),
    ]);

    $user->oauthProviders()->create([
      'provider' => $provider,
      'provider_user_id' => $sUser->getId(),
      'access_token' => $sUser->token,
      'refresh_token' => $sUser->refreshToken,
    ]);

    return $user;
  }
}
