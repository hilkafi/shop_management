<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('purchase_id')->nullable();
            $table->integer('sale_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->double('quantity')->nullable();
            $table->double('rate')->nullable();
            $table->double('amount');
            $table->integer('from_head_id');
            $table->integer('from_subhead_id')->nullable();
            $table->integer('to_head_id');
            $table->integer('to_subhead_id')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
