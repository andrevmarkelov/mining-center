<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_categories', function (Blueprint $table) {
            $table->id();
            $table->string('alias')->unique()->nullable();
            $table->enum('status', ['0','1'])->default(0);
            $table->enum('sitemap', ['0', '1'])->default(1);
            $table->timestamps();
        });

        Schema::create('news_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_category_id')->constrained()->onDelete('cascade');
            $table->string('locale')->index();

            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('meta_h1')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->unique(['news_category_id', 'locale'], 'nct_news_category_id_locale_unique');
        });

        Schema::create('news_news_category', function (Blueprint $table) {
            $table->foreignId('news_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('news_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_news_category');
        Schema::dropIfExists('news_category_translations');
        Schema::dropIfExists('news_categories');
    }
}
