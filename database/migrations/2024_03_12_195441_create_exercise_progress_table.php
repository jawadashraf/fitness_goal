<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExerciseProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercise_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('workout_progress_id')->constrained('workout_progress')->onDelete('cascade');
            $table->foreignId('workout_exercise_id')->constrained('workout_day_exercises')->onDelete('cascade');
            $table->string('metric_type')->nullable();
            $table->integer('value')->nullable(); // Consider specifying the unit in a comment or documentation
            $table->text('notes')->nullable(); // Optional notes for observations or comments

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
        Schema::dropIfExists('exercise_progress');
    }
}
