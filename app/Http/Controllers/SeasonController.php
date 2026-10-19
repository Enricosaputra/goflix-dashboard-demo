<?php

namespace App\Http\Controllers;

use App\Models\Season;

class SeasonController extends NamedResourceController
{
    protected string $model = Season::class;

    protected string $route = 'seasons';

    protected string $title = 'Seasons';

    protected string $singular = 'season';

    protected string $description = 'Season names shared by every series, e.g. "Season 1".';

    protected array $countColumn = ['Episodes', 'episodes'];

    protected bool $hasActiveFlag = false;
}
