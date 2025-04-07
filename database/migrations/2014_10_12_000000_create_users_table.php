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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('tel_no',100)->nullable();
            $table->string('telegram_id',100)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->integer('theme')->default(0);
            $table->integer('sidebar')->default(0);
            $table->integer('production_report')->default(0);
            $table->integer('can_buy')->default(0);
            $table->integer('buy_assent')->default(0);
            $table->integer('confirm_buy')->default(0);
            $table->integer('can_exit')->default(0);
            $table->integer('confirm_exit')->default(0);
            $table->integer('can_count_all')->default(0);
            $table->integer('can_confirm_count')->default(0);
            $table->integer('can_request_report')->default(0);
            $table->integer('quality_control')->default(0);
            $table->integer('confirm_quality_control')->default(0);
            $table->integer('work_order_level')->default(0);
            $table->integer('active')->default(1);
            $table->string('profile_photo_path', 2048)->nullable();
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
        Schema::dropIfExists('users');
    }
};
