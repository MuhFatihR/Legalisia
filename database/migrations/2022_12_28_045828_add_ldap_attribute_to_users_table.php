<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLdapAttributeToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile')->nullable();
            $table->string('gender')->nullable();
            $table->string('location')->nullable();
            $table->string('badgeid')->nullable();
            $table->string('nik')->nullable();
            $table->string('level')->nullable();
            $table->string('title')->nullable();
            $table->string('section')->nullable();
            $table->string('department')->nullable();
            $table->string('division')->nullable();
            $table->string('directorate')->nullable();
            $table->string('company')->nullable();
            $table->string('supervisor')->nullable();
            $table->string('manager')->nullable();
            $table->text('photo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['title','mobile', 'photo']);
        });
    }
}
