<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTickerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticker', function (Blueprint $table) {
            $table->increments('id');
            $table->string('eventType')->nullable();
            $table->bigInteger('eventTime')->nullable();
            $table->string('symbol')->nullable();
            $table->float('priceChange',8)->nullable();
            $table->float('percentChange',8)->nullable();
            $table->float('averagePrice',8)->nullable();
            $table->float('prevClose',8)->nullable();
            $table->float('close',8)->nullable();
            $table->float('closeQty',8)->nullable();
            $table->float('bestBid',8)->nullable();
            $table->float('bestBidQty',8)->nullable();
            $table->float('bestAsk',8)->nullable();
            $table->float('bestAskQty',8)->nullable();
            $table->float('open',8)->nullable();
            $table->float('high',8)->nullable();
            $table->float('low',8)->nullable();
            $table->float('volume',13)->nullable();
            $table->float('quoteVolume',13)->nullable();
            $table->bigInteger('openTime')->nullable();
            $table->bigInteger('closeTime')->nullable();
            $table->integer('firstTradeId')->nullable();
            $table->integer('lastTradeId')->nullable();
            $table->integer('numTrades')->nullable();

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
        Schema::dropIfExists('ticker');
    }
}
