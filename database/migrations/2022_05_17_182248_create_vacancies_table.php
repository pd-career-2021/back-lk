<?php

use App\Models\Employer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('title', 128);
            $table->string('desc', 1000);
            $table->string('img_path')->nullable();
            $table->integer('salary')->default(0);
            $table->enum('salary_type', ['От', 'До', 'По договоренности']);
            $table->enum('employment_type', [
                'Проектная работа',
                'Стажировка',
                'Частичная занятость',
                'Полная занятость'
            ]);
            $table->enum('work_experience', [
                'Без опыта',
                'Не имеет значения',
                'От 1 года до 3 лет',
                'От 3 до 6 лет',
                'Более 6 лет'
            ]);
            $table->string('duties', 1000);
            $table->string('conditions', 1000);
            $table->string('requirements', 1000);
            $table->string('workplace', 255);
            $table->string('map_link', 1000)->nullable();
            $table->foreignIdFor(Employer::class);
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
        Schema::dropIfExists('vacancies');
    }
}
