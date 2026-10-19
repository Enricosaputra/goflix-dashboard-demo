<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieRequest;
use App\Models\Genre;
use App\Models\Language;
use App\Models\Movie;
use App\Models\Person;
use Illuminate\Support\Facades\DB;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::with(['language', 'genres'])->withCount('watches')
            ->when(request('access') === 'free', fn ($q) => $q->where('is_premium', false))
            ->when(request('access') === 'premium', fn ($q) => $q->where('is_premium', true))
            ->when(request('q'), fn ($q, $term) => $q->whereRaw('lower(title) like ?', ['%'.mb_strtolower($term).'%']))
            ->latest('release_year')->orderBy('title')->paginate(15)->withQueryString();

        return view('movies.index', compact('movies'));
    }

    public function create()
    {
        return view('movies.form', ['movie' => new Movie(['is_active' => true, 'rent_days' => 2, 'max_quality' => '1080p', 'release_year' => now()->year]), ...$this->choices()]);
    }

    public function store(MovieRequest $request)
    {
        DB::transaction(function () use ($request) {
            $movie = Movie::create($request->safe()->except(['genres', 'people']));
            $movie->genres()->sync($request->input('genres', []));
            $movie->people()->sync($request->input('people', []));
        });

        return to_route('movies.index')->with('status', __('Saved.'));
    }

    public function edit(Movie $movie)
    {
        return view('movies.form', ['movie' => $movie->load('genres', 'people'), ...$this->choices()]);
    }

    public function update(MovieRequest $request, Movie $movie)
    {
        DB::transaction(function () use ($request, $movie) {
            $movie->update($request->safe()->except(['genres', 'people']));
            $movie->genres()->sync($request->input('genres', []));
            $movie->people()->sync($request->input('people', []));
        });

        return to_route('movies.index')->with('status', __('Saved.'));
    }

    public function destroy(Movie $movie)
    {
        if ($reason = $movie->deletionBlocker()) {
            return back()->with('error', $reason);
        }
        $movie->delete();

        return back()->with('status', __('Deleted.'));
    }

    private function choices(): array
    {
        return [
            'languages' => Language::where('is_active', true)->orderBy('name')->get(),
            'genres' => Genre::where('is_active', true)->orderBy('name')->get(),
            'people' => Person::where('is_active', true)->orderBy('name')->get(),
        ];
    }
}
