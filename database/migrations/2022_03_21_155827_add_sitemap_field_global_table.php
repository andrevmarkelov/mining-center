<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSitemapFieldGlobalTable extends Migration
{
    public $tables;

    public function __construct()
    {
        $this->tables = [
            'firmwares',
            'firmware_categories',
            'data_centers',
            'equipments',
            'coins',
            'wiki',
            'wiki_categories',
            'news'
        ];
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->tables as $item) {
            Schema::table($item, function (Blueprint $table) {
                $table->enum('sitemap', ['0', '1'])->default(1);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->tables as $item) {
            Schema::table($item, function (Blueprint $table) {
                $table->dropColumn('sitemap');
            });
        }
    }
}
