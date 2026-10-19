<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Actors, actresses and directors. Production keeps them in one `cast` table with a type.
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Season names shared by every series ("Season 1", "Season 2"...), as in production's `session` table.
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('language_id')->constrained()->restrictOnDelete();
            $table->unsignedSmallInteger('release_year');
            $table->unsignedSmallInteger('duration_minutes');
            $table->string('maturity_rating', 10)->nullable();
            $table->decimal('imdb_rating', 3, 1)->nullable();
            $table->boolean('is_premium')->default(false);
            // A premium movie can also be rented on its own; null means it is subscription only.
            $table->unsignedInteger('rent_price')->nullable();
            $table->unsignedTinyInteger('rent_days')->default(2);
            $table->boolean('is_downloadable')->default(false);
            $table->string('max_quality', 10)->default('1080p');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Production stores these as comma-separated ids in text columns; the demo uses pivot tables.
        Schema::create('genre_movie', function (Blueprint $table) {
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->foreignId('genre_id')->constrained()->restrictOnDelete();
            $table->primary(['movie_id', 'genre_id']);
        });

        Schema::create('movie_person', function (Blueprint $table) {
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->foreignId('person_id')->constrained('people')->restrictOnDelete();
            $table->primary(['movie_id', 'person_id']);
        });

        Schema::create('shows', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('language_id')->constrained()->restrictOnDelete();
            $table->unsignedSmallInteger('release_year');
            $table->string('maturity_rating', 10)->nullable();
            $table->decimal('imdb_rating', 3, 1)->nullable();
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('genre_show', function (Blueprint $table) {
            $table->foreignId('show_id')->constrained()->cascadeOnDelete();
            $table->foreignId('genre_id')->constrained()->restrictOnDelete();
            $table->primary(['show_id', 'genre_id']);
        });

        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->constrained()->cascadeOnDelete();
            $table->foreignId('season_id')->constrained()->restrictOnDelete();
            $table->unsignedSmallInteger('episode_number');
            $table->string('title');
            $table->unsignedSmallInteger('duration_minutes');
            $table->boolean('is_premium')->default(false);
            $table->timestamps();
            $table->unique(['show_id', 'season_id', 'episode_number']);
        });
    }

    public function down(): void
    {
        foreach (['episodes', 'genre_show', 'shows', 'movie_person', 'genre_movie', 'movies', 'seasons', 'people', 'languages', 'genres'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
