<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkoutProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workout_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_schedule_id')->constrained('workout_schedules')->onDelete('cascade');
            $table->date('date_completed')->nullable();
            $table->unsignedTinyInteger('percent_completed')->default(0); // 0-100
            $table->text('notes')->nullable(); // Optional notes
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
        Schema::dropIfExists('workout_progress');
    }
}
