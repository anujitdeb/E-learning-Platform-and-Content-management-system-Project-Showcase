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
        Schema::create('course_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')
                ->constrained('courses')
                ->onDelete('cascade');
            $table->foreignId('language_id')
                ->constrained('languages')
                ->onDelete('cascade');
            $table->integer('created_by')->nullable();
            $table->string('name')->unique();
            $table->string('course_duration')->nullable();
            $table->string('lectures_qty')->nullable();
            $table->string('project_qty')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_contents');
    }
};
