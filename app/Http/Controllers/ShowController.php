<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowRequest;
use App\Models\Genre;
use App\Models\Language;
use App\Models\Season;
use App\Models\Show;
use Illuminate\Support\Facades\DB;

class ShowController extends Controller
{
    public function index()
    {
        $shows = Show::with(['language', 'genres', 'episodes'])
            ->when(request('q'), fn ($q, $term) => $q->whereRaw('lower(title) like ?', ['%'.mb_strtolower($term).'%']))
            ->orderBy('title')->paginate(15)->withQueryString();

        return view('shows.index', compact('shows'));
    }

    public function create()
    {
        return view('shows.form', ['show' => new Show(['is_active' => true, 'release_year' => now()->year]), ...$this->choices()]);
    }

    public function store(ShowRequest $request)
    {
        DB::transaction(function () use ($request) {
            $show = Show::create($request->safe()->except(['genres', 'episodes']));
            $show->genres()->sync($request->input('genres', []));
            $show->setEpisodes($request->input('episodes', []));
        });

        return to_route('shows.index')->with('status', __('Saved.'));
    }

    public function edit(Show $show)
    {
        return view('shows.form', ['show' => $show->load('genres', 'episodes'), ...$this->choices()]);
    }

    public function update(ShowRequest $request, Show $show)
    {
        DB::transaction(function () use ($request, $show) {
            $show->update($request->safe()->except(['genres', 'episodes']));
            $show->genres()->sync($request->input('genres', []));
            $show->setEpisodes($request->input('episodes', []));
        });

        return to_route('shows.index')->with('status', __('Saved.'));
    }

    public function destroy(Show $show)
    {
        if ($reason = $show->deletionBlocker()) {
            return back()->with('error', $reason);
        }
        $show->delete();

        return back()->with('status', __('Deleted.'));
    }

    private function choices(): array
    {
        return [
            'languages' => Language::where('is_active', true)->orderBy('name')->get(),
            'genres' => Genre::where('is_active', true)->orderBy('name')->get(),
            'seasons' => Season::orderBy('id')->get(),
        ];
    }
}
