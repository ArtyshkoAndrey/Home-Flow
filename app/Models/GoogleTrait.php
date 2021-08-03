<?php

namespace App\Models;

use Database\Factories\GoogleTraitFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\GoogleTrait
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static GoogleTraitFactory factory(...$parameters)
 * @method static Builder|GoogleTrait newModelQuery()
 * @method static Builder|GoogleTrait newQuery()
 * @method static Builder|GoogleTrait query()
 * @method static Builder|GoogleTrait whereCreatedAt($value)
 * @method static Builder|GoogleTrait whereId($value)
 * @method static Builder|GoogleTrait whereName($value)
 * @method static Builder|GoogleTrait whereUpdatedAt($value)
 * @mixin Eloquent
 */
class GoogleTrait extends Model
{
  use HasFactory;

  /**
   * Columns
   *
   * @var string[]
   */
  protected $fillable = [
    'name'
  ];
}
