<?php

namespace Database\Seeders;

use App\Models\GoogleType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run(): void
  {
    // \App\Models\User::factory(10)->create();
    DB::table('google_types')->insert([
      'name' => 'action.devices.types.LIGHT'
    ]);
    DB::table('google_types')->insert([
      'name' => 'action.devices.types.SECURITYSYSTEM'
    ]);
    DB::table('google_types')->insert([
      'name' => 'action.devices.types.SWITCH'
    ]);
    DB::table('google_types')->insert([
      'name' => 'action.devices.types.SENSOR'
    ]);

    DB::table('google_types')->insert([
      'name' => 'action.devices.types.CURTAIN'
    ]);

    DB::table('google_traits')->insert([
      'name' => 'action.devices.traits.TemperatureControl'
    ]);

    DB::table('google_traits')->insert([
      'name' => 'action.devices.traits.EnergyStorage'
    ]);

    DB::table('google_traits')->insert([
      'name' => 'action.devices.traits.SensorState'
    ]);

    DB::table('google_traits')->insert([
      'name' => 'action.devices.traits.OnOff'
    ]);

    DB::table('google_traits')->insert([
      'name' => 'action.devices.traits.OpenClose'
    ]);
  }
}
