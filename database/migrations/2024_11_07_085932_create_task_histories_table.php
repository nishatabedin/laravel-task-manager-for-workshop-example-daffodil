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
        Schema::create('task_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks');
            $table->foreignId('history_created_by')->constrained('users');
            $table->string('action');
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_histories', function (Blueprint $table) {
            $table->dropForeign(['task_id']);
            $table->dropForeign(['history_created_by']);
        });
        Schema::dropIfExists('task_histories');
    }

    
};
