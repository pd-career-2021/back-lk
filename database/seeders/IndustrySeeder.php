<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $industries = array(
            'Менеджмент',
            'IT',
            'Консалтинг',
            'Маркетинг и PR',
            'Продажи',
            'Работа с людьми',
            'Инженерия',
            'Прикладная математика',
            'Программирование',
            'Разработка игр',
            'Финансы',
            'Бухгалтерия',
            'Фундаментальная наука',
            'Медицина и здоровье',
            'Биология',
            'Химия',
            'Фармацевтика',
            'Дизайн',
            'Культура и творчество',
            'Туризм',
            'Право',
            'Гос. служба',
            'Международные отношения',
            'HR',
            'Журналистика',
            'Образование',
            'Социальная работа',
            'Лингвистика',
            'Безопасность',
            'Сельское хозяйство',
            'Строительство',
            'Рабочая специальность',
            'Обслуживающий персонал',
            'Транспорт',
            'Логистика',
            'Спорт',
            'Недвижимость',
            'Страхование',
            'Архитектура',
            'Другое'
        );

        foreach ($industries as $industry) {
            DB::table('industries')->insertOrIgnore([
                'title' => $industry,
                'created_at' =>  date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
