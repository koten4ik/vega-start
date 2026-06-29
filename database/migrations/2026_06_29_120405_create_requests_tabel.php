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
        Schema::create('a_requests', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->index(); // timestamp, NOT NULL, INDEX
            $table->string('url', 255)->index(); // varchar(255), NOT NULL, INDEX
            $table->string('method', 10)->nullable(); // varchar(10), NULL
            $table->smallInteger('response_status')->nullable(); // smallint(6), NULL
            $table->string('content_type', 20)->nullable(); // varchar(20), NULL
            $table->text('request_data')->nullable(); // text, NULL
            $table->integer('user_id')->nullable(); // int(11), NULL
            $table->string('uuid', 50)->nullable()->index(); // varchar(50), NULL, INDEX
            $table->string('uuid2', 50)->nullable()->index(); // varchar(50), NULL, INDEX
            $table->integer('uuid_count')->nullable(); // int(11), NULL
            $table->string('ip', 15)->nullable(); // varchar(15), NULL
            $table->string('user_agent', 255)->nullable(); // varchar(255), NULL
            $table->integer('response_time')->nullable(); // int(11), NULL

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests_tabel');
    }
};
