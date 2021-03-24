<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoomIdInModules extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up(): void
  {
    Schema::table('modules', function (Blueprint $table) {
      $table->foreignId('room_id')
        ->nullable()
        ->constrained()
        ->onUpdate('cascade')
        ->onDelete('set null');
      $table->string('ico')->nullable()->default('fas fa-question');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down(): void
  {
    Schema::table('models', function (Blueprint $table) {
      $table->removeColumn('room_id');
      $table->removeColumn('ico');
    });
  }
}
