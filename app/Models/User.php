<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Contracts\JWTSubject;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $photo_url
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|OAuthProvider[] $oauthProviders
 * @property-read int|null $oauth_providers_count
 * @method static UserFactory factory(...$parameters)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @mixin Eloquent
 */
class User extends Authenticatable implements JWTSubject //, MustVerifyEmail
{
  use Notifiable,
    HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'email',
    'password',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  /**
   * The accessors to append to the model's array form.
   *
   * @var array
   */
  protected $appends = [
    'photo_url',
  ];

  /**
   * Get the profile photo URL attribute.
   *
   * @return string
   */
  public function getPhotoUrlAttribute(): string
  {
    return vsprintf('https://www.gravatar.com/avatar/%s.jpg?s=200&d=%s', [
      md5(strtolower($this->email)),
      $this->name ? urlencode("https://ui-avatars.com/api/$this->name") : 'mp',
    ]);
  }

  /**
   * Get the oauth providers.
   *
   * @return HasMany
   */
  public function oauthProviders(): HasMany
  {
    return $this->hasMany(OAuthProvider::class);
  }

  /**
   * Send the password reset notification.
   *
   * @param string $token
   * @return void
   */
  public function sendPasswordResetNotification($token): void
  {
    $this->notify(new ResetPassword($token));
  }

  /**
   * Send the email verification notification.
   *
   * @return void
   */
  public function sendEmailVerificationNotification(): void
  {
    $this->notify(new VerifyEmail);
  }

  /**
   * @return int
   */
  public function getJWTIdentifier(): int
  {
    return $this->getKey();
  }

  /**
   * @return array
   */
  public function getJWTCustomClaims(): array
  {
    return [];
  }
}
