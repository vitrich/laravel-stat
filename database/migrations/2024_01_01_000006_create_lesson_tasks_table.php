<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lesson_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->json('tasks_data');
            $table->json('answers')->nullable();
            $table->integer('score')->nullable();
            $table->integer('correct_count')->default(0);
            $table->integer('total_count')->default(10);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            
            $table->unique(['lesson_id', 'student_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('lesson_tasks');
    }
};
