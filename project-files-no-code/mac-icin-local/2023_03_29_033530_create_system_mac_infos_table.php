<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_mac_infos', function (Blueprint $table) {
            $table->id();
            $table->string('ip')->nullable();
            $table->string('mac')->nullable();
            $table->string('device')->nullable();
            $table->string('device_ver')->nullable();
            $table->string('browser')->nullable();
            $table->string('browser_ver')->nullable();
            $table->string('user')->nullable();
            $table->string('location')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_mac_infos');
    }
};
