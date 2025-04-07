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
        Schema::create('erp_stock_movements', function (Blueprint $table) {
            $table->id();
            $table->integer('item_id');
            $table->integer('type');
            $table->string('content')->nullable();
            $table->integer('increased_warehouse_id')->default(0);      // artan depo
            $table->integer('dwindling_warehouse_id')->default(0);      // azalan depo
            $table->integer('sender_user')->default(0);                 // gönderen kullanıcı
            $table->integer('approval_user')->default(0);               // onaylayan kullanıcı
            $table->float('amount', 20, 4);                             // miktar
            $table->float('old_warehouse_amount', 20, 4)->nullable();   // eski depo miktarı
            $table->float('old_total_amount', 20, 4)->nullable();       // eski toplam miktar
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
        Schema::dropIfExists('erp_stock_movements');
    }
};
