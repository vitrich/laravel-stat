<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('group_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->date('transfer_date');
            $table->text('reason')->nullable();
            $table->timestamps();
            
            $table->index(['student_id', 'transfer_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_histories');
    }
};
