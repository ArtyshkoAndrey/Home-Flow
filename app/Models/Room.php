<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
