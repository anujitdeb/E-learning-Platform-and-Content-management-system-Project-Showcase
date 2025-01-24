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
        Schema::create('offer_notices', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->string('title');
            $table->text('description');
            $table->string('image')->nullable();
            $table->string('thumbnail')->nullable();
            $table->integer('status')->default(1)->comment('1 = Both, 2 = Guest, 3 = Student');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_notices');
    }
};
