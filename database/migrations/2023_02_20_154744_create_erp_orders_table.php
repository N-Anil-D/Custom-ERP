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
        Schema::create('erp_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->string('customer_name');
            $table->date('order_date');
            $table->date('delivery_date');
            $table->integer('order_status')->default(0);
            $table->text('order_note')->nullable();
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
        Schema::dropIfExists('erp_orders');
    }
};
