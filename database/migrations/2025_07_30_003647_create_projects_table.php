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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
           
            $table->string('name'); // e.g. "teamhanko/hanko"
            $table->text('description')->nullable();
            $table->longText('long_description')->nullable();
            $table->json('tech_stack')->nullable(); // e.g. ["Go", "WebAuthn"]
            $table->string('repository_url');
            $table->string('website_url')->nullable();
            $table->unsignedInteger('stars')->default(0);
            $table->unsignedInteger('open_issues_count')->default(0);
            $table->unsignedInteger('contributors_count')->default(0);
            $table->timestamp('last_updated')->nullable();
            $table->string('difficulty')->nullable(); // Optional difficulty tag
            $table->json('codebase_overview')->nullable(); // AI-generated
            $table->json('contribution_guide')->nullable(); // AI-generated
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
