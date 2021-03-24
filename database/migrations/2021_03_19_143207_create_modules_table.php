<?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  class CreateModulesTable extends Migration
  {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
      Schema::create('modules', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('google_index')->unique();
        $table->foreignId('google_type_id')
          ->constrained('google_types')
          ->onUpdate('cascade')
          ->onDelete('cascade');
        $table->string('data')->nullable();
        $table->json('meta')->nullable();
        $table->string('mqtt');
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
      Schema::dropIfExists('modules');
    }
  }
