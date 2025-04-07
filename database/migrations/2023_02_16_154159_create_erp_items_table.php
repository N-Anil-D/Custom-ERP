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
        Schema::create('erp_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->index();
            $table->string('code');
            $table->string('name');
            $table->string('content')->nullable();
            $table->integer('type')->default(0);
            $table->string('barcode')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('erp_items');
    }
};
