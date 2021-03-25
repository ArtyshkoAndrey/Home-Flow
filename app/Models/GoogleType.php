<?php

namespace App\Models;

use Database\Factories\GoogleTypeFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\GoogleType
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static GoogleTypeFactory factory(...$parameters)
 * @method static Builder|GoogleType newModelQuery()
 * @method static Builder|GoogleType newQuery()
 * @method static Builder|GoogleType query()
 * @method static Builder|GoogleType whereCreatedAt($value)
 * @method static Builder|GoogleType whereId($value)
 * @method static Builder|GoogleType whereName($value)
 * @method static Builder|GoogleType whereType($value)
 * @method static Builder|GoogleType whereUpdatedAt($value)
 * @mixin Eloquent
 */
class GoogleType extends Model
{
  use HasFactory;

  /**
   * Columns
   *
   * @var string[]
   */
  protected $fillable = [
    'name',
    'type',
  ];
}
