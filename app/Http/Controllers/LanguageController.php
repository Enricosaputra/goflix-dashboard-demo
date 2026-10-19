<?php

namespace App\Http\Controllers;

use App\Models\Language;

class LanguageController extends NamedResourceController
{
    protected string $model = Language::class;

    protected string $route = 'languages';

    protected string $title = 'Languages';

    protected string $singular = 'language';

    protected string $description = 'The spoken language of a movie or series.';

    protected array $countColumn = ['Movies', 'movies'];
}
