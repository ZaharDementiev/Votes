<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('transaction', function (Blueprint $table) {
//            $table->bigIncrements('id');
//            $table->bigInteger('user_id');
//            $table->bigInteger('to_id');
//            $table->integer('amount');
//            $table->smallInteger('transaction_status');
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction');
    }
}
