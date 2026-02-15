<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->date('date');
            $table->string('subject', 100)->default('Математика');
            $table->string('grade', 20)->default('5');
            $table->text('theory_content');
            $table->integer('duration_minutes')->default(40);
            $table->integer('test_duration_minutes')->default(5);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['date', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('lessons');
    }
};
