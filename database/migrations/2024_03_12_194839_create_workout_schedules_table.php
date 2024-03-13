<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkoutSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workout_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('workout_id')->constrained('workouts')->onDelete('cascade');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->date('date_completed')->nullable();
            $table->unsignedTinyInteger('percent_completed')->default(0); // 0-100
            $table->text('notes')->nullable();
            $table->string('color')->nullable();
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
        Schema::dropIfExists('workout_schedules');
    }
}
