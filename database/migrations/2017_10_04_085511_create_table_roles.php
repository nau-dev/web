<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateTableRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
        });

        Schema::create('users_roles', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->uuid('role_id');
            $table->primary(['user_id', 'role_id'])->index();
        });

        Schema::table('users_roles', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('role_id')->references('id')->on('roles');
        });

        $rolesArray = [];

        foreach (['admin', 'agent', 'chief_advertiser', 'advertiser', 'user'] as $rolename) {
            $rolesArray[$rolename]['id']         = Uuid::generate('4')->string;
            $rolesArray[$rolename]['name']       = $rolename;
        }

        DB::table('roles')->insert($rolesArray);

        $users = DB::table('users')->get();
        $i     = 0;
        foreach ($users as $user) {
            DB::table('users_roles')->insert([
                [
                    'user_id'    => $user->id,
                    'role_id'    => $rolesArray['user']['id']
                ],
                [
                    'user_id'    => $user->id,
                    'role_id'    => $rolesArray['advertiser']['id']
                ]
            ]);
            $i++;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_roles');
        Schema::dropIfExists('roles');
    }
}
