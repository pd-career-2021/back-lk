<?php

use App\Models\Vacancy;
use App\Models\VacancyFunction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacanciesFunctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancies_functions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Vacancy::class);
            $table->foreignIdFor(VacancyFunction::class);
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
        Schema::dropIfExists('vacancies_functions');
    }
}
