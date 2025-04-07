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
        Schema::create('erp_warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('content')->nullable();
            $table->string('can_take_from_outside')->default(0);
            $table->string('can_send_to_outside')->default(0);
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
        Schema::dropIfExists('erp_warehouses');
    }
};
