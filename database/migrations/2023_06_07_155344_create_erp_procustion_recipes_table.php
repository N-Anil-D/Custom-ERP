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
        Schema::create('erp_production_recipes', function (Blueprint $table) {
            $table->id();
            $table->integer('item_id');
            $table->text('recipe_name')->nullable();
            $table->integer('recipe_creator_id')->default(0);
            $table->integer('recipe_id')->nullable();
            $table->float('amount', 20, 4)->nullable();
            $table->string('waste')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('erp_procustion_recipes');
    }
};
