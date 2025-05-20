<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->string('document_number', 14)->unique()->index();
            $table->string('document_type' , 4);
            $table->string('person_type', 2);
            $table->string('email', 50)->unique();
            $table->string('cellphone', 20)->nullable();
            $table->string('user_type', 20);
            $table->string('password');
            $table->datetimes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
