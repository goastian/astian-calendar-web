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
        Schema::create('calendars', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('title');
            $table->longText('body')->nullable();
            $table->string('resource')->nullable();
            $table->string('banner')->nullable();
            $table->boolean('public')->default(0);
            $table->dateTime('meeting');
            $table->uuid('user_id');
            $table->timestamps();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendars');
    }
};
