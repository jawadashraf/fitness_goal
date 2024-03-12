<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoalTypeUnitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goal_type_unit', function (Blueprint $table) {
            $table->foreignId('goal_type_id')->constrained('goal_types');
            $table->foreignId('unit_type_id')->constrained('unit_types');
            $table->primary(['goal_type_id', 'unit_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goal_type_unit');
    }
}
