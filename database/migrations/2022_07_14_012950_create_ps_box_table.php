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
        Schema::create('ps_box', function (Blueprint $table) {
            $table->id();
            $table->integer('userId');
            $table->string('definition');
            $table->longText('def1')->nullable();
            $table->longText('def2')->nullable();
            $table->longText('def3')->nullable();
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
        Schema::dropIfExists('ps_box');
    }
};
