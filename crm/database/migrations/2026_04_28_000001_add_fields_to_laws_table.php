<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('laws', function (Blueprint $table) {
            $table->string('country', 8)->nullable()->after('code');
            $table->string('type', 32)->nullable()->after('country');
            $table->string('status', 16)->default('active')->after('type');

            $table->date('published_at')->nullable()->after('status');

            $table->string('official_url')->nullable()->after('published_at');
            $table->string('word_url')->nullable()->after('official_url');
            $table->string('consultant_url')->nullable()->after('word_url');

            $table->json('tags')->nullable()->after('consultant_url');

            $table->text('comment')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('laws', function (Blueprint $table) {
            $table->dropColumn([
                'country',
                'type',
                'status',
                'published_at',
                'official_url',
                'word_url',
                'consultant_url',
                'tags',
                'comment',
            ]);
        });
    }
};