<?php

namespace Database\Seeders;

use App\Models\UnitType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unitTypes = [
            'kilometers',
            'miles',
            'minutes',
            'hours',
            'calories',
            'reps',
            'sets',
            'watts',
            'laps',
            'levels',
            'bpm', // beats per minute for heart rate
            // ... any other unit types you want to include
        ];

        foreach ($unitTypes as $unitName) {
            UnitType::firstOrCreate(['name' => $unitName]);
        }
    }
}
