<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employer_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('category');
            $table->text('description');
            $table->string('required_skills');
            $table->string('qualifications')->nullable();
            $table->string('experience')->nullable();
            $table->string('location');
            $table->string('employment_type');
            $table->unsignedInteger('slots')->default(1);
            $table->date('deadline');
            $table->string('contact_info')->nullable();
            $table->string('status')->default('open');           // draft | open | closed
            $table->string('moderation')->default('approved');   // approved | removed (set by admin)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};
