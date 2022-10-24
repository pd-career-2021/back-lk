<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45)->unique();
            $table->string('desc', 1000)->nullable();
            $table->timestamps();
        });

        DB::table('roles')->insert(
            [
                [
                    'name' => 'Admin',
                    'desc' => 'Администратор',
                    'created_at' =>  date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => 'Student',
                    'desc' => 'Студент',
                    'created_at' =>  date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => 'Employer',
                    'desc' => 'Представитель организации',
                    'created_at' =>  date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => 'User',
                    'desc' => 'Временная роль',
                    'created_at' =>  date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
