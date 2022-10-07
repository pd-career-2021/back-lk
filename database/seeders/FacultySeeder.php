<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faculties = [
            'Факультет информационных технологий',
            'Транспортный факультет',
            'Факультет машиностроения',
            'Факультет химической технологии и биотехнологии',
            'Факультет урбанистики и городского хозяйства',
            'Факультет экономики и управления',
            'Институт графики и искусства книги имени В. А. Фаворского (Высшая школа печати и медиаиндустрии)',
            'Институт издательского дела и журналистики (Высшая школа печати и медиаиндустрии)',
            'Полиграфический институт (Высшая школа печати и медиаиндустрии)',
            'Факультет базовых компетенций',
        ];

        foreach ($faculties as $faculty) {
            DB::table('faculties')->insertOrIgnore([
                'title' => $faculty,
                'desc' => 'Описание факультета "'.$faculty.'"',
                'created_at' =>  date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
