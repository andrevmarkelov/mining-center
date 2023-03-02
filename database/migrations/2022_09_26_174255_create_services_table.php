<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('equipment_type');
            $table->string('alias')->unique()->nullable();
            $table->json('contacts')->nullable();
            $table->integer('sort_order')->default(0);
            $table->enum('status', ['0','1'])->default(0);
            $table->enum('sitemap', ['0', '1'])->default(1);
            $table->timestamps();
        });

        Schema::create('service_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('locale')->index();

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->unique(['service_id', 'locale']);
        });

        Schema::create('city_service', function (Blueprint $table) {
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('city_service');
        Schema::dropIfExists('service_translations');
        Schema::dropIfExists('services');
    }
}
