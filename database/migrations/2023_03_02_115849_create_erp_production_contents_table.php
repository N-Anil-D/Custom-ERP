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
        Schema::create('erp_production_contents', function (Blueprint $table) {
            $table->id();
            $table->integer('production_id');
            $table->integer('main_item_id');
            $table->integer('item_id');
            $table->integer('warehouse_id');
            $table->float('amount', 20, 4);
            $table->float('wastage', 20, 4);
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
        Schema::dropIfExists('erp_production_contents');
    }
};
