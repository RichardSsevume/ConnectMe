<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('youth');                    // youth | employer | admin
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
            $table->string('profile_photo')->nullable();                 // profile picture / company logo
            $table->string('account_status')->default('active');         // active | suspended
            $table->string('verification_status')->default('pending');   // pending | verified | rejected
            // skilled youth
            $table->string('professional_title')->nullable();
            $table->string('skills')->nullable();
            $table->text('bio')->nullable();
            $table->string('education_level')->nullable();
            $table->text('work_experience')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->boolean('visible_to_employers')->default(false);
            // employers
            $table->string('company_name')->nullable();
            $table->string('industry')->nullable();
            $table->text('company_description')->nullable();
            $table->string('website')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'location', 'profile_photo', 'account_status', 'verification_status',
                'professional_title', 'skills', 'bio', 'education_level', 'work_experience', 'portfolio_url',
                'visible_to_employers', 'company_name', 'industry', 'company_description', 'website']);
        });
    }
};
