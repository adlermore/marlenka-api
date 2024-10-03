<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('home_highlighties', function (Blueprint $table) {
            $table->id();
            $table->string("title")->nullable();
            $table->string("description_one")->nullable();
            $table->string("description_two")->nullable();
            $table->string("description_three")->nullable();
            $table->string("image_path")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_highlighties');
    }
};
