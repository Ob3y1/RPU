<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade'); 
            $table->foreignId('unis_id')->constrained('specializations__per__universities')->onDelete('cascade'); 
            $table->foreignId('r_type_id')->constrained('r_types')->onDelete('cascade'); 
            $table->enum('request_status', ['pending', 'Accepted ', 'rejected'])->default('pending'); 
            $table->string('certificate_country');
            $table->string('total');
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
        Schema::dropIfExists('requests');
    }
}
