<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->boolean('reserved')->default(false);
            $table->string('reason')->nullable();
            $table->string('cnp',10)->nullable();
            $table->date('birthdate')->nullable();
            $table->string('phone',12)->nullable();
            $table->unsignedBigInteger('section_in_institutions_id');
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('section_in_institutions_id')->references('id')->on('sections_in_institutions')->onDelete('cascade');
 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dates', function (Blueprint $table) {
            $table->dropForeign(['section_in_institutions_id']);
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['patient_id']);

            
        });
        Schema::dropIfExists('dates');
    }
};
