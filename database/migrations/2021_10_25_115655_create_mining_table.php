<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mining', function (Blueprint $table) {
            $table->id();
            $table->string('link')->nullable();
            $table->enum('status', ['0','1'])->default(0);
            $table->timestamps();
        });

        Schema::create('mining_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mining_id')->constrained('mining')->onDelete('cascade');
            $table->string('locale')->index();

            $table->string('title');
            $table->text('description')->nullable();

            $table->unique(['mining_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mining_translations');
        Schema::dropIfExists('mining');
    }
}
