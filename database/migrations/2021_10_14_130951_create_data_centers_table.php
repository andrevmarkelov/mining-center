<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_centers', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('power_type');
            $table->string('alias')->unique()->nullable();
            $table->integer('sort_order')->default(0);
            $table->enum('status', ['0','1'])->default(0);
            $table->timestamps();
        });

        Schema::create('data_center_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_center_id')->constrained()->onDelete('cascade');
            $table->string('locale')->index();

            $table->string('title');
            $table->text('description')->nullable();
            $table->text('add_description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('action_text')->nullable();

            $table->unique(['data_center_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_center_translations');
        Schema::dropIfExists('data_centers');
    }
}
