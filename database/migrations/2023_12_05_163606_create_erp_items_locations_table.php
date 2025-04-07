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
        Schema::create('erp_items_locations', function (Blueprint $table) {
            $table->id();
            $table->integer('item_id');
            $table->integer('warehouse_id');
            $table->string('p1',20)->nullable();
            $table->string('p2',20)->nullable();
            $table->string('p3',20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('erp_items_locations');
    }
};
