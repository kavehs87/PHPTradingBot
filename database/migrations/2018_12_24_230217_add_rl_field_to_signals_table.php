<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRlFieldToSignalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('signals', function (Blueprint $table) {
            $table->string('rl')->nullable();
            $table->string('module')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('signals', function (Blueprint $table) {
            $table->dropColumn('rl');
            $table->dropColumn('module');
        });
    }
}
