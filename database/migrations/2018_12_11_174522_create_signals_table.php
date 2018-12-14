<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('market')->nullable();
            $table->float('lastprice',10,0)->nullable();
            $table->string('signalmode')->default('buy');
            $table->string('exchange')->default('binance');
            $table->timestamp('time')->nullable();
            $table->float('basevolume',10,0)->nullable();
            $table->string('signalID')->nullable();
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
        Schema::dropIfExists('signals');
    }
}
