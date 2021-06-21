<?php

namespace App\Models;

use Database\Factories\TypeFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Type
 *
 * @property int $id
 * @property string $name
 * @property GoogleType $google_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|GoogleTrait[] $google_traits
 * @property-read int|null $google_traits_count
 * @method static TypeFactory factory(...$parameters)
 * @method static Builder|Type newModelQuery()
 * @method static Builder|Type newQuery()
 * @method static Builder|Type query()
 * @method static Builder|Type whereCreatedAt($value)
 * @method static Builder|Type whereGoogleType($value)
 * @method static Builder|Type whereId($value)
 * @method static Builder|Type whereName($value)
 * @method static Builder|Type whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Type extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'type'
  ];

  /**
   * Many Traits module
   *
   * @return BelongsToMany
   */
  public function google_traits(): BelongsToMany
  {
    return $this->belongsToMany(GoogleTrait::class, 'type_google_traits');
  }

  /**
   * GoogleType module
   *
   * @return BelongsTo
   */
  public function google_type(): BelongsTo
  {
    return $this->belongsTo(GoogleType::class, 'google_type', 'id');
  }
}
