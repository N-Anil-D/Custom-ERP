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
        Schema::create('kgs_kimlikleri', function (Blueprint $table) {
            $table->id();
            $table->char('kgs_id',11)->unique('kgs_id');
            $table->string('name');
            $table->softDeletes();
            $table->smallInteger('shift');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kgs_kimlikleris');
    }
};
