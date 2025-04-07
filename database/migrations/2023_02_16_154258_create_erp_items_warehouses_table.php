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
        Schema::create('erp_items_warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->index();
            $table->foreignId('warehouse_id')->index();
            $table->float('amount', 20, 4);
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
        Schema::dropIfExists('erp_items_warehouses');
    }
};
