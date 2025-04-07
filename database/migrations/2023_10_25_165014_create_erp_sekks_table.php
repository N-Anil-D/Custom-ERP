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
        Schema::create('erp_sekk', function (Blueprint $table) {
            $table->id();
            $table->string('lot_no')->nullable();
            $table->integer('user_id');
            $table->integer('warehouse_id')->nullable();
            $table->integer('item_id');
            $table->integer('clean_status');
            $table->string('work_order_no')->nullable();
            $table->float('amount', 20, 4);
            $table->integer('general_status');
            $table->text('text')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('erp_sekk');
    }
};
