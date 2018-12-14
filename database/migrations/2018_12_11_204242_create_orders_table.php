<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('buyId')->nullable();
            $table->string('symbol');
            $table->integer('orderId');
            $table->string('clientOrderId');
            $table->bigInteger('transactTime');
            $table->float('price', 10, 0);
            $table->float('origQty', 10, 0);
            $table->float('executedQty', 10, 0);
            $table->float('cummulativeQuoteQty', 10, 0);
            $table->string('status');
            $table->string('timeInForce')->default('GTC');
            $table->string('type');
            $table->string('side');
            $table->boolean('trailing')->default(0);
            $table->float('maxFloated')->default(0);
            $table->float('takeProfit')->nullable();
            $table->float('stopLoss')->nullable();
            $table->float('trailingTakeProfit')->nullable();
            $table->float('trailingStopLoss')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
