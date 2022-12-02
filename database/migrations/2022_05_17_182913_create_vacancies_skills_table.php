<?php

use App\Models\Vacancy;
use App\Models\CoreSkill;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacanciesSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancies_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Vacancy::class);
            $table->foreignIdFor(CoreSkill::class);
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
        Schema::dropIfExists('vacancies_skills');
    }
}
