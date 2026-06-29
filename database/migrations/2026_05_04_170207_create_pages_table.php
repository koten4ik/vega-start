<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('module')->index()->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->text('content')->nullable();
            $table->boolean('display')->nullable();
            $table->boolean('display_menu')->nullable();
            $table->boolean('display_menu_footer')->nullable();
            $table->boolean('is_indexable')->nullable();
            $table->integer('rank')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
