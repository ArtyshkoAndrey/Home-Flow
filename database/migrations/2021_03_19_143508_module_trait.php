<?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  class ModuleTrait extends Migration
  {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
      Schema::create('modules_traits', function (Blueprint $table) {
        $table->id();
        $table->foreignId('google_trait_id')
          ->constrained('google_traits')
          ->onUpdate('cascade')
          ->onDelete('cascade');
        $table->foreignId('module_id')
          ->constrained('modules')
          ->onUpdate('cascade')
          ->onDelete('cascade');
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
      Schema::dropIfExists('modules_traits');
    }
  }
