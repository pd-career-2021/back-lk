<?php

namespace Database\Seeders;

use App\Models\{Application, CompanyType, CoreSkill, Employer, Event, User, Student, Industry, News, Role, Social, Vacancy};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            FacultySeeder::class, // B4 Users/Employers/Students/Vacanies
            IndustrySeeder::class, // B4 Employers
            AudienceSeeder::class, // B4 Events
            ApplicationStatusSeeder::class, // B4 Applications
        ]);

        User::factory()->state([
            'email' => 'career@mospolytech.ru',
            'password' => bcrypt('mospolypass'),
            'name' => 'Администратор',
            'surname' => 'Центра Карьеры',
            'sex' => 'male',
            'faculty_id' => 1,
        ])->create()->roles()->sync(Role::find(1));

        // DB::table('users')->insert([
        //     'email' => 'career@mospolytech.ru',
        //     'password' => bcrypt('mospolypass'),
        //     'name' => 'Администратор',
        //     'surname' => 'Центра Карьеры',
        //     'sex' => 'male',
        //     'faculty_id' => 1,
        // ]);

        // DB::table('role_users')->insert([
        //     'user_id' => 1,
        //     'role_id' => 1
        // ]);

        // User::factory()->create(); // B4 Students/Employers. Create users with a temporary role. USE STUDENT/EMPLOYER FACTORY INSTEAD
        // Industry::factory(10)->create(); // B4 Employers. USE SEEDER INSTEAD

        Student::factory(20)->create(); // B4 Applications
        CompanyType::factory(5)->create(); // B4 Employers
        Employer::factory(20)
            ->has(
                Social::factory()
                    ->count(random_int(1, 3))
                    ->state(function (array $attributes, Employer $employer) {
                        return ['employer_id' => $employer->id];
                    })
            )
            ->create()
            ->each(function ($employer) {
                $employer->industries()->sync(DB::table('industries')->inRandomOrder()->take(random_int(1, 3))->pluck('id')->toArray());
            }); // B4 Events/Vacancies/News
        Event::factory(20)->create()->each(function($event) {
            $event->employers()->sync(DB::table('employers')->inRandomOrder()->take(random_int(1, 2))->pluck('id')->toArray());
        }); 
        News::factory(20)->create();
        CoreSkill::factory(20)->create(); // B4 Vacancies
        Vacancy::factory(20)->create()->each(function($vacancy) {
            $vacancy->faculties()->sync(DB::table('faculties')->inRandomOrder()->take(random_int(1, 2))->pluck('id')->toArray());
            $vacancy->skills()->sync(DB::table('core_skills')->inRandomOrder()->take(random_int(3, 6))->pluck('id')->toArray());
        }); // B4 Applications 
        Application::factory(40)->create();
    }
}
