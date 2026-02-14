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
        Schema::create('tariff_configurations', function (Blueprint $table) {
            $table->unsignedBigInteger('tariff_id');
            $table->unsignedBigInteger('configuration_id');
            $table->timestamps();

            $table->foreign('tariff_id')->references('id')->on('tariffs');
            $table->foreign('configuration_id')->references('id')->on('connection_configurations');

            $table->primary(['configuration_id','tariff_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tariff_configurations');
    }
};
