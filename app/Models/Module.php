<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Module extends Model
{
  use HasFactory;

  /**
   * @var string[]
   */
  protected $fillable = [
    'name',
    'google_index',
    'data',
    'meta',
    'mqtt',
  ];

  /**
   * @var string[]
   */
  protected $casts = [
    'meta' => 'json'
  ];

  /**
   * @return BelongsTo
   */
  public function type(): BelongsTo
  {
    return $this->belongsTo(GoogleType::class, 'google_type_id', 'id');
  }

  /**
   * @return BelongsToMany
   */
  public function traits(): BelongsToMany
  {
    return $this->belongsToMany(GoogleTrait::class, 'modules_traits');
  }

  public function room (): BelongsTo
  {
    return $this->belongsTo(Room::class);
  }
}
