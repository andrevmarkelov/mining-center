<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinRatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_rating', function (Blueprint $table) {
            $table->foreignId('coin_id')->constrained()->onDelete('cascade');
            $table->foreignId('rating_id')->constrained('ratings')->onDelete('cascade');
            $table->json('pool_data')->nullable();
            $table->float('hashrate', 30, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coin_rating');
    }
}
