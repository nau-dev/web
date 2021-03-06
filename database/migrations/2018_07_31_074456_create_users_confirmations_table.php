<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersConfirmationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users_confirmations');

        Schema::create('users_confirmations', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('user_id');
            $table->string('token', 64)->unique();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('users_confirmations', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        if (false === Schema::hasColumn('users', 'confirmed')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('confirmed')->default(false);
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('confirmed');
        });

        Schema::dropIfExists('users_confirmations');
    }
}
