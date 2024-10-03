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
        Schema::create('contact_us', function (Blueprint $table) {
            $table->id();
            $table->string("phone_number")->nullable();
            $table->string("whatsapp")->nullable();
            $table->string("email")->nullable();
            $table->string("facebook_link")->nullable();
            $table->string("instagram_link")->nullable();
            $table->string("gmail_link")->nullable();
            $table->string("linkedin_link")->nullable();
            $table->string("twitter_link")->nullable();
            $table->string("youtube_link")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_us');
    }
};
