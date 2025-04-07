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
        Schema::create('erp_stock_takings', function (Blueprint $table) {
            $table->id();
            $table->integer('item_id');
            $table->integer('warehouse_id');
            $table->integer('status')->default(0);
            $table->float('amount', 20, 4);
            $table->integer('counting_user');
            $table->integer('approver_user')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('erp_stock_takings');
    }
};
