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
        Schema::create('a_logs', function (Blueprint $table) {
            $table->id(); // Первичный ключ, bigint(20) UNSIGNED AUTO_INCREMENT
            $table->timestamp('created_at')->nullable(false); // В вашем описании нет default, поэтому nullable(false)
            $table->integer('type')->nullable()->index(); // type int(11) с индексом
            $table->text('message'); // text utf8mb4_unicode_ci
            $table->text('url')->nullable(); // text utf8mb4_unicode_ci
            $table->integer('user_id')->nullable()->index(); // user_id int(11) с индексом
            $table->text('params')->nullable(); // text utf8mb4_unicode_ci
            $table->text('request_data')->nullable(); // text utf8mb4_unicode_ci
            $table->string('uuid', 50)->nullable()->index(); // uuid varchar(50) с индексом
            $table->string('uuid2', 50)->nullable(); // uuid2 varchar(50)
            $table->string('ip', 15)->nullable(); // ip varchar(15)
            $table->string('user_agent', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log');
    }
};
