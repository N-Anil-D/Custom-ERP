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
        Schema::create('erp_productions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('item_id');
            $table->integer('warehouse_id');
            $table->text('lot_no')->nullable();
            $table->text('work_order_no')->nullable();
            $table->string('name');
            $table->float('amount', 20, 4)->nullable();
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('erp_productions');
    }
};
