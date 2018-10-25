<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersRegister extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_register', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id',50);
            $table->string('user_name',50);
            $table->string('user_age',10);
            $table->string('user_height',6);
            $table->string('user_weight',6);
            $table->string('exercise_week',10);
            $table->integer('status',11);            
            // $table->timestamp('created_at')->nullable();
            // $table->timestamp('updated_at')->nullable();
            //$table->string('dateofbirth',10);
           // $table->timestamps()->nullable();
            //$table->softDeletes()->nullable();
            //$table->timestamp('updated_at');
            //$table->rememberToken();
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_register');
    }
}
