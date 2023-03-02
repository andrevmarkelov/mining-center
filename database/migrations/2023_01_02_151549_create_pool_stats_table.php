<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoolStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pool_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coin_id')->constrained()->onDelete('cascade');
            $table->string('miner');
            $table->bigInteger('block_height');
            $table->bigInteger('difficulty');
            $table->timestamp('time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pool_stats');
    }
}
