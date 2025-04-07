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
        Schema::create('kgs_cikis', function (Blueprint $table) {
            $table->id();
            $table->char('kgs_id',11);
            $table->timestamp('cikis', $precision = 0)->nullable();
            // $table->integer('calisma_dakika')->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kgs_cikis');
    }
};
