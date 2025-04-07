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
        Schema::create('erp_send_products', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('last_warehouse_id')->nullable();
            $table->integer('item_id');
            $table->string('lot_no');
            $table->string('fatura_irsaliye_no',255);
            $table->float('amount', 20, 4);
            $table->string('send_to')->nullable();
            $table->integer('status');
            $table->date('send_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('erp_send_products');
    }
};
