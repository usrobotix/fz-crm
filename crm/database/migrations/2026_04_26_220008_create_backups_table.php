<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('kind')->default('backup');
            $table->string('file_preset')->nullable();
            $table->json('formats')->nullable();
            $table->json('local_paths')->nullable();
            $table->json('remote_paths')->nullable();
            $table->unsignedBigInteger('size_bytes')->default(0);
            $table->string('status')->default('queued');
            $table->text('error_message')->nullable();
            $table->string('initiated_by')->default('user');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('progress_percent')->default(0);
            $table->string('current_step')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backups');
    }
};
