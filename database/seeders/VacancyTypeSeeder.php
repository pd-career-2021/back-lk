<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VacancyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vacancy_types = [
            'Проектная работа',
            'Стажировка',
            'Частичная занятость',
            'Полная занятость',
        ];

        foreach ($vacancy_types as $vacancy_type) {
            DB::table('vacancies_types')->insertOrIgnore([
                'title' => $vacancy_type,
                'created_at' =>  date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
