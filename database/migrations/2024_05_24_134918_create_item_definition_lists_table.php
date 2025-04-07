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
        Schema::create('item_definition_lists', function (Blueprint $table) {
            $table->id();
            $table->string('listId');
            $table->string('name');
            $table->date('entry_date');
            $table->string('irsaliye')->nullable();
            $table->string('company_name');
            $table->float('amount', 20, 4);
            $table->string('lot');
            $table->date('last_use_date')->nullable();
            $table->string('controller')->nullable();
            $table->string('suitability')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_definition_lists');
    }
};
