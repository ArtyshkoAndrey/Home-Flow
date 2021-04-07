<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TypesGoogleTraits extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('type_google_traits', function (Blueprint $table) {
      $table->id();
      $table->foreignId('type_id')
        ->constrained('types')
        ->onDelete('cascade')
        ->onUpdate('cascade');
      $table->foreignId('google_trait_id')
        ->constrained('google_traits')
        ->onDelete('cascade')
        ->onUpdate('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('type_google_traits');
  }
}
