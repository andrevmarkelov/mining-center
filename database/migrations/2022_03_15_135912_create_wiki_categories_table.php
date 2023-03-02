<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWikiCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wiki_categories', function (Blueprint $table) {
            $table->id();
            $table->string('alias')->unique()->nullable();
            $table->enum('status', ['0','1'])->default(0);
            $table->timestamps();
        });

        Schema::create('wiki_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wiki_category_id')->constrained()->onDelete('cascade');
            $table->string('locale')->index();

            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('meta_h1')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->unique(['wiki_category_id', 'locale'], 'wct_wiki_category_id_locale_unique');
        });

        Schema::table('wiki', function (Blueprint $table) {
            $table->foreignId('wiki_category_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wiki_category_translations');
        Schema::dropIfExists('wiki_categories');

        Schema::table('wiki', function (Blueprint $table) {
            $table->dropForeign('wiki_wiki_category_id_foreign');
        });
    }
}
