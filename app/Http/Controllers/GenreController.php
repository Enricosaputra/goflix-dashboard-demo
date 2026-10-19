<?php

namespace App\Http\Controllers;

use App\Models\Genre;

class GenreController extends NamedResourceController
{
    protected string $model = Genre::class;

    protected string $route = 'genres';

    protected string $title = 'Genres';

    protected string $singular = 'genre';

    protected string $description = 'Genres group movies and series in the app.';

    protected array $countColumn = ['Movies', 'movies'];
}
