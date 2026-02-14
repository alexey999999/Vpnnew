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
        Schema::create('tariff_users', function (Blueprint $table) {
            $table->unsignedBigInteger('tariff_id');
            $table->unsignedBigInteger('user_id');
            $table->datetime('active_to');
            $table->timestamps();

            $table->foreign('tariff_id')->references('id')->on('tariffs');
            $table->foreign('user_id')->references('id')->on('users');

            $table->primary(['user_id','tariff_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tariff_users');
    }
};
