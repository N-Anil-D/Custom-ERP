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
        Schema::create('erp_items_personal_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('item_id');
            $table->double('amount', 20, 4);
            $table->string('alert_condition',10);
            $table->tinyInteger('warned');
            $table->tinyInteger('perma')->default(0);
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
        Schema::dropIfExists('erp_items_personal_actions');
    }
};
