<?php

use App\Models\VacancyType;
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
            $table->string('title', 64);
            $table->string('desc', 1000);
            $table->string('short_desc', 255);
            $table->string('img_path')->nullable();
            $table->string('link', 1000);
            $table->decimal('salary', 10);
            $table->string('workplace', 255);
            $table->string('level', 64);
            $table->string('skills', 1000);
            $table->string('map', 1000)->nullable();
            $table->foreignIdFor(VacancyType::class);
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
