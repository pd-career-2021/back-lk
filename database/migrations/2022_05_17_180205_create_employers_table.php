<?php

use App\Models\User;
use App\Models\CompanyType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 128);
            $table->string('short_name', 64)->nullable();
            $table->string('desc', 1000);
            $table->string('img_path')->nullable();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(CompanyType::class);
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
        Schema::dropIfExists('employers');
    }
}
