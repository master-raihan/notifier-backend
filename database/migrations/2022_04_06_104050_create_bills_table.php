<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2);
            $table->string('currency');
            $table->string('payee');
            $table->text('note');
            $table->dateTime('due_date');
            $table->enum('transaction_type',['payable', 'receivable']);
            $table->integer('repeat')->nullable();
            $table->string('repeat_unit')->nullable();
            $table->integer('notification')->default(5);
            $table->tinyInteger('status')->default(1);
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('category_id')->references('id')->on('categories');
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
        Schema::dropIfExists('bills');
    }
}
