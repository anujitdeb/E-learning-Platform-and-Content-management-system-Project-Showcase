<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by')->nullable();
            $table->integer('course_category_id')->nullable();
            $table->integer('course_id')->nullable();
            $table->string('slug')->unique();
            $table->string('offline_icon')->nullable();
            $table->string('online_icon')->nullable();
            $table->string('seminar_thumbnail')->nullable();
            $table->string('video_thumbnail')->nullable();
            $table->string('video_id')->nullable();
            $table->integer('offline_price')->nullable();
            $table->integer('online_price')->nullable();
            $table->integer('status')->default(1)->comment("1 = Main course, 2 = Diploma course");
            $table->boolean('is_active')->default(false);
            $table->string('bg_color')->nullable();
            $table->string('btn_color')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
