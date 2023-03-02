<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coins', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('alias')->unique()->nullable();
            $table->string('code')->unique();
            $table->boolean('show_home')->default(0);
            $table->integer('whattomine_coin_id')->nullable();
            $table->string('whattomine_unit')->nullable();
            $table->json('profit_per_unit')->nullable();
            $table->json('chart_data')->nullable();
            $table->json('cost_by_exchange')->nullable();
            $table->enum('status', ['0','1'])->default(0);
            $table->timestamp('parse_time')->nullable();
            $table->timestamps();
        });

        Schema::create('coin_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coin_id')->constrained()->onDelete('cascade');
            $table->string('locale')->index();

            $table->text('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('meta_h1')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->unique(['coin_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coin_translations');
        Schema::dropIfExists('coins');
    }
}
