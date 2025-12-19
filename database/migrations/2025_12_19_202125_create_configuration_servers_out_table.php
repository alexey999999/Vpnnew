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
        Schema::create('configuration_servers_out', function (Blueprint $table) {
            $table->unsignedBigInteger('connection_configuration_id');
            $table->unsignedBigInteger('server_out_id');
            $table->timestamps();

            $table->foreign('connection_configuration_id')->references('id')->on('connection_configurations');
            $table->foreign('server_out_id')->references('id')->on('servers');

            $table->primary(['connection_configuration_id','server_out_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuration_servers_out');
    }
};
