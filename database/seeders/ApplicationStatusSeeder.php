<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            'Открыто',
            'На рассмотрении',
            'Отклонено',
            'Завершено',
        ];

        foreach ($statuses as $status) {
            DB::table('application_statuses')->insertOrIgnore([
                'name' => $status,
                'desc' => 'Описание статуса "'.$status.'"',
                'created_at' =>  date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
