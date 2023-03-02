<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManufacturersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacturers', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->enum('status', ['0','1'])->default(0);
            $table->timestamps();
        });

        Schema::table('equipments', function (Blueprint $table) {
            $table->foreignId('manufacturer_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->dropForeign('equipments_manufacturer_id_foreign');
            $table->dropColumn('manufacturer_id');
        });
        Schema::dropIfExists('manufacturers');
    }
}
