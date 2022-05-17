<?php

use App\Models\Student;
use App\Models\Vacancy;
use App\Models\ApplicationStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicatonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicatons', function (Blueprint $table) {
            $table->id();
            $table->string('desc', 1000);
            $table->foreignIdFor(Student::class);
            $table->foreignIdFor(Vacancy::class);
            $table->foreignIdFor(ApplicationStatus::class);
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
        Schema::dropIfExists('applicatons');
    }
}
