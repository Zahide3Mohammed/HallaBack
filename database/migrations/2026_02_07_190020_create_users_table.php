<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('nom');
        $table->string('prenom');
        $table->integer('age');
        $table->string('paye');
        $table->string('sexe');
        $table->string('tel');
        $table->string('email')->unique();
        $table->string('password');
        $table->string('photo')->nullable();
        $table->timestamps();
    });
}


    
    
};
