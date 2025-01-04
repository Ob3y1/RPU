<?php

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
            $table->string('name', 20)->nullable(false);
            $table->string('phone_number')->unique();
            $table->string('email')->unique();
            $table->date('birth_date');
            $table->string('password');
            $table->string('nationality')->nullable();
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade')->default(3);
            $table->foreignId('default_payment_method_id')->constrained('payment_methods')->onDelete('cascade')->default(1);
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
