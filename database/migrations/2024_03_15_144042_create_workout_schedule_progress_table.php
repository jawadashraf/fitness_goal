<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkoutScheduleProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workout_schedule_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('exercise_id')->constrained()->onDelete('cascade');
            $table->integer('progress')->default(0); // Represents the progress for each exercise, adjust as needed
            $table->timestamps();

            $table->unique(['workout_schedule_id', 'exercise_id'], 'workout_schedule_exercise_unique');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workout_schedule_progress');
    }
}
