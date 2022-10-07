<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AudienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $audiences = array(
            'Поступающие',
            'Студенты',
            'Выпускники',
            'Магистры',
            'Аспиранты'
        );

        foreach ($audiences as $audience) {
            DB::table('audiences')->insertOrIgnore([
                'name' => $audience,
                'created_at' =>  date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
