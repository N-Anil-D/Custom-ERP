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
        Schema::create('fixtures_items', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('barcode')->nullable();
            $table->string('location')->nullable();
            $table->string('section')->nullable();
            $table->string('floor')->nullable();
            $table->string('room_code')->nullable();
            $table->string('content')->nullable();
            $table->string('item_name')->nullable();
            $table->string('brand')->nullable();
            $table->integer('amount');
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
        Schema::dropIfExists('fixtures_items');
    }
};
