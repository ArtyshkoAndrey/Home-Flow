<?php

namespace App\Models;

use Database\Factories\RoomFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Room
 *
 * @property int $id
 * @property string $name
 * @property string $index
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Module[] $modules
 * @property-read int|null $modules_count
 * @method static RoomFactory factory(...$parameters)
 * @method static Builder|Room newModelQuery()
 * @method static Builder|Room newQuery()
 * @method static Builder|Room query()
 * @method static Builder|Room whereCreatedAt($value)
 * @method static Builder|Room whereDescription($value)
 * @method static Builder|Room whereId($value)
 * @method static Builder|Room whereIndex($value)
 * @method static Builder|Room whereName($value)
 * @method static Builder|Room whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Room extends Model
{
  use HasFactory;

  /**
   * Columns
   *
   * @var string[]
   */
  protected $fillable = [
    'name',
    'index',
    'description'
  ];

  /**
   * Modules in self Room
   *
   * @return HasMany
   */
  public function modules(): HasMany
  {
    return $this->hasMany(Module::class);
  }
}
