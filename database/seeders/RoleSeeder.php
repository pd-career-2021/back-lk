<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert(
            [
                [
                    'name' => 'Администратор',
                    'slug' => 'admin',
                    'desc' => 'Администратор',
                    'permissions' => '{
                        "platform.index": "1", 
                        "platform.systems.roles": "1", 
                        "platform.systems.users": "1", 
                        "platform.systems.faculties": "1", 
                        "platform.systems.attachment": "1", 
                        "platform.employment.vacancies": "1", 
                        "platform.employment.applications": "1"
                    }',
                    'created_at' =>  date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => 'Студент',
                    'slug' => 'student',
                    'desc' => 'Студент',
                    'permissions' => '{"platform.index": false}',
                    'created_at' =>  date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => 'Работодатель',
                    'slug' => 'employer',
                    'desc' => 'Представитель организации',
                    'permissions' => '{"platform.index": false}',
                    'created_at' =>  date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => 'Пользователь',
                    'slug' => 'user',
                    'desc' => 'Временная роль',
                    'permissions' => '{"platform.index": false}',
                    'created_at' =>  date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            ]
        );
    }
}
