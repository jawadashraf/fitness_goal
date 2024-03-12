<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('goal_type_id')->constrained('goal_types');
            $table->foreignId('unit_type_id')->constrained('unit_types');
            $table->double('target_value', 8, 2); // The numeric target for the goal
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->enum('status', ['ACTIVE', 'COMPLETED', 'FAILED'])->default('ACTIVE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goals');
    }
}
