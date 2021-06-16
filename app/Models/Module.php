<?php

namespace App\Models;

use Database\Factories\ModuleFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Verta;

/**
 * App\Models\Module
 *
 * @property int $id
 * @property string $name
 * @property string $google_index
 * @property int $google_type_id
 * @property string|null $data
 * @property array|null $meta
 * @property string $mqtt
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $room_id
 * @property string|null $ico
 * @property-read Room|null $room
 * @property-read Collection|GoogleTrait[] $traits
 * @property-read int|null $traits_count
 * @property-read GoogleType $type
 * @method static ModuleFactory factory(...$parameters)
 * @method static Builder|Module newModelQuery()
 * @method static Builder|Module newQuery()
 * @method static Builder|Module query()
 * @method static Builder|Module whereCreatedAt($value)
 * @method static Builder|Module whereData($value)
 * @method static Builder|Module whereGoogleIndex($value)
 * @method static Builder|Module whereGoogleTypeId($value)
 * @method static Builder|Module whereIco($value)
 * @method static Builder|Module whereId($value)
 * @method static Builder|Module whereMeta($value)
 * @method static Builder|Module whereMqtt($value)
 * @method static Builder|Module whereName($value)
 * @method static Builder|Module whereRoomId($value)
 * @method static Builder|Module whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $type_id
 * @method static Builder|Module whereTypeId($value)
 */
class Module extends Model
{
  use HasFactory;

  /**
   * Columns
   *
   * @var string[]
   */
  protected $fillable = [
    'name',
    'google_index',
    'data',
    'meta',
    'mqtt',
    'ico'
  ];

  /**
   * Type columnds
   *
   * @var string[]
   */
  protected $casts = [
    'meta' => 'json'
  ];

  protected $appends = [
    'updated_date'
  ];

  /**
   * Type module
   *
   * @return BelongsTo
   */
  public function type(): BelongsTo
  {
    return $this->belongsTo(Type::class, 'type_id', 'id');
  }

  /**
   * Room module
   *
   * @return BelongsTo
   */
  public function room(): BelongsTo
  {
    return $this->belongsTo(Room::class);
  }

  public function getUpdatedDateAttribute($value): string
  {
    return $this->updated_at->format('d.m.Y H:i');
  }
}
