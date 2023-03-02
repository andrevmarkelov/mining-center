<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirmwaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firmwares', function (Blueprint $table) {
            $table->id();
            $table->string('alias')->unique()->nullable();
            $table->enum('status', ['0','1'])->default(0);
            $table->timestamps();
        });

        Schema::create('firmware_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('firmware_id')->constrained('firmwares')->onDelete('cascade');
            $table->string('locale')->index();

            $table->string('title');
            $table->text('description')->nullable();
            $table->text('add_description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->unique(['firmware_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('firmware_translations');
        Schema::dropIfExists('firmwares');
    }
}
