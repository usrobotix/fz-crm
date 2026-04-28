<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('law_expert_opinions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('law_id')->constrained('laws')->cascadeOnDelete();

            $table->string('expert_name')->nullable();
            $table->text('opinion')->nullable();

            $table->string('video_url')->nullable();
            $table->longText('video_transcript')->nullable();

            $table->string('file_path')->nullable();
            $table->string('resource_url')->nullable();

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('law_expert_opinions');
    }
};