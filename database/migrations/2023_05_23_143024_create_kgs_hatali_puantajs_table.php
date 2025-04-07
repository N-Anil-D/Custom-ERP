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
        Schema::create('kgs_hatali_puantajs', function (Blueprint $table) {
            $table->id();
            $table->char('kgs_id',11);
            $table->dateTime('giris')->nullable();
            $table->dateTime('cikis')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kgs_hatali_puantajs');
    }
};
