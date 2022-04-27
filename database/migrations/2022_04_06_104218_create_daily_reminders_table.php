<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_reminders', function (Blueprint $table) {
            $table->id();
            $table->integer('time_before')->nullable();
            $table->unsignedBigInteger('bill_id');
            $table->unsignedBigInteger('user_id');
            $table->json('data');
            $table->tinyInteger('status')->default(0);
            $table->foreign('bill_id')->references('id')->on('bills');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('daily_reminders');
    }
}
