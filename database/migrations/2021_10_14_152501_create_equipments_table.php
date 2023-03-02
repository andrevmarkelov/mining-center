<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->float('price')->nullable();
            $table->float('hashrate', 30, 2);
            $table->float('power');
            $table->json('profit_data')->nullable();
            $table->tinyInteger('available')->default(0);
            $table->string('alias')->unique()->nullable();
            $table->enum('status', ['0','1'])->default(0);
            $table->timestamp('parse_time')->nullable();
            $table->timestamps();
        });

        Schema::create('equipment_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipments')->onDelete('cascade');
            $table->string('locale')->index();

            $table->string('title');
            $table->string('add_title')->nullable();
            $table->text('description')->nullable();
            $table->text('add_description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->unique(['equipment_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment_translations');
        Schema::dropIfExists('equipments');
    }
}
