<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->unsignedBigInteger('server_type_id');
            $table->float('protocol_version');
//            ipv4 varbinary(16)
            $table->unsignedBigInteger('country_id');
            $table->string('url', 255);
            $table->string('main_token', 255);
            $table->string('remote_token', 255);
            $table->float('current_load', 5, 2)->default(0);
            $table->float('avg_load', 5, 2)->default(0);
            $table->unsignedMediumInteger('port')->nullable();
            $table->string('password', 255)->nullable();
            $table->string('encryption_method', 50)->nullable();

            $table->foreign('server_type_id')->references('id')->on('server_types');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE `servers` ADD `ipv4` VARBINARY(16) NOT NULL AFTER `protocol_version`');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
