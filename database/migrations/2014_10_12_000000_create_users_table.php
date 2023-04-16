<?php

use App\Models\Role;
use App\Models\Faculty;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45);
            $table->string('surname', 45);
            $table->string('email', 45)->unique();
            $table->string('password');
            $table->enum('sex', ['male', 'female']);
            $table->string('img_path')->nullable();
            $table->foreignIdFor(Faculty::class);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
