<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdColumnToMultipleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('body_parts', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
        });
        Schema::table('exercises', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
        });
        Schema::table('equipment', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
        });
        Schema::table('workouts', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
        });
        Schema::table('workout_types', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('body_parts', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
        Schema::table('exercises', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
        Schema::table('workouts', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
        Schema::table('workout_types', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
