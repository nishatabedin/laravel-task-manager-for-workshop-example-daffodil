<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->foreignId('category_id')->constrained('categories'); 
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['Pending', 'In Progress', 'Completed']);
            $table->softDeletes(); 
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['deleted_by']);
            $table->dropForeign(['category_id']);
        });
        Schema::dropIfExists('tasks');
    }
};
