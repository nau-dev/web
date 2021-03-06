<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropRetailTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('retail_types');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('retail_types', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->uuid('category_id');
            $table->string('slug', 64)->index()->unique();
            $table->string('name', 64);
        });
        Schema::table('retail_types', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }
}
