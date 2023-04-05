<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->string('hasrole')->default('user');
            $table->string('name')->default('UU');
            $table->string('adress')->default('');
            $table->string('phoneNumber')->default('');
            $table->string('initials');
            $table->string('image')->default('');
            $table->string('email')->unique();
            $table->decimal('salary')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('vacationDays')->default(25);
            $table->integer('vacationDays_left')->nullable();
            $table->integer('sicknessLeave')->default(0);
            $table->date('start_of_work')->nullable();
            $table->json('workdays')->nullable();
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
};
