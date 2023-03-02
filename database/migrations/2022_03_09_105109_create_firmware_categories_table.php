<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirmwareCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firmware_categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('alias')->unique()->nullable();
            $table->enum('status', ['0','1'])->default(0);
            $table->timestamps();
        });

        Schema::create('firmware_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('firmware_category_id')->constrained()->onDelete('cascade');
            $table->string('locale')->index();

            $table->text('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('meta_h1')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->unique(['firmware_category_id', 'locale'], 'fct_firmware_category_id_locale_unique');
        });

        Schema::table('firmwares', function (Blueprint $table) {
            $table->foreignId('firmware_category_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('firmware_category_translations');
        Schema::dropIfExists('firmware_categories');

        Schema::table('firmwares', function (Blueprint $table) {
            $table->dropForeign('firmwares_firmware_category_id_foreign');
        });
    }
}
