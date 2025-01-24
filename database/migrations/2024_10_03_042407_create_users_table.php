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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->integer('student_id')->nullable();
            $table->integer('admission_id')->nullable();
            $table->string('number')->nullable();
            $table->text('location')->nullable();
            $table->string('image')->nullable();
            $table->string('password')->nullable();
            $table->boolean('is_complete')->default(false);
            $table->boolean('is_student')->default(false);
            $table->boolean('is_admitted')->default(false);
            $table->dateTime('number_verified_at')->nullable();
            $table->dateTime('email_verified_at')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_number')->default(false);
            $table->string('device_token')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
