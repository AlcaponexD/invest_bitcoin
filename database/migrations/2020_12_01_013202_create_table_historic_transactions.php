<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableHistoricTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historic_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type',['sell','buy','deposit']);
            $table->double('btc_price',19,10)->nullable();
            $table->double('amount',19,10);
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
        Schema::dropIfExists('historic_transactions');
    }
}
