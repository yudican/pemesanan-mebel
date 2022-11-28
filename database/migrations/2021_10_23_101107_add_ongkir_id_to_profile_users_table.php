<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOngkirIdToProfileUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profile_users', function (Blueprint $table) {
            $table->foreignId('ongkir_id')->after('user_id');
            $table->foreign('ongkir_id')->references('id')->on('ongkir')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile_users', function (Blueprint $table) {
            //
        });
    }
}
