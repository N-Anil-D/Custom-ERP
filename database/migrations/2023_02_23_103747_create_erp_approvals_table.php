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
        Schema::create('erp_approvals', function (Blueprint $table) {
            $table->id();
            $table->integer('item_id');
            $table->text('content')->nullable();
            $table->text('content_answer')->nullable();
            $table->string('file')->nullable();
            $table->integer('type');
            $table->integer('status')->default(0);
            $table->integer('notify')->default(0);
            $table->integer('sender_user')->default(0);             // gönderen kullanıcı
            $table->integer('answer_user')->default(0);             // onaylayan/iptal eden kullanıcı
            $table->integer('increased_warehouse_id')->default(0);  // artan depo
            $table->integer('dwindling_warehouse_id')->default(0);  // azalan depo
            $table->float('amount', 20, 4);                         // miktar
            $table->text('lot_no')->nullable();
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
        Schema::dropIfExists('erp_approvals');
    }
};
