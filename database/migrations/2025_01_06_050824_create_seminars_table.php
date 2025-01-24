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
        Schema::create('seminars', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by')->nullable();
            $table->foreignId('seminar_detail_id')->constrained('seminar_details')->onDelete('cascade');
            $table->integer('location_id')->nullable();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->integer('type')->comment('1 = Offline, 2 = Online');
            $table->datetime('datetime');
            $table->string('link')->nullable();
            $table->string('platform')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('seminar_type')->comment('1 = Seminar, 2 = Workshop');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seminars');
    }
};
