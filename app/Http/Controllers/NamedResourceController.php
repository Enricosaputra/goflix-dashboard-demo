<?php

namespace App\Http\Controllers;

use App\Http\Requests\NameRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

// Shared CRUD for master data that only has a name: genres, languages, seasons.
abstract class NamedResourceController extends Controller
{
    /** @var class-string<Model> */
    protected string $model;

    protected string $route;      // e.g. "genres"

    protected string $title;      // page title, translated in the view

    protected string $singular;   // e.g. "genre"

    protected string $description;

    /** Extra column shown in the list: [heading, relation to count]. */
    protected array $countColumn;

    protected bool $hasActiveFlag = true;

    public function index()
    {
        $items = $this->model::query()
            ->withCount($this->countColumn[1])
            ->when(request('q'), fn (Builder $q, $term) => $q->whereRaw('lower(name) like ?', ['%'.mb_strtolower($term).'%']))
            ->orderBy('name')->paginate(20)->withQueryString();

        return view('named.index', ['items' => $items, 'meta' => $this->meta()]);
    }

    public function create()
    {
        return view('named.form', ['item' => new $this->model(['is_active' => true]), 'meta' => $this->meta()]);
    }

    public function store(NameRequest $request)
    {
        $this->model::create($request->validated());

        return to_route("{$this->route}.index")->with('status', __('Saved.'));
    }

    public function edit(string $id)
    {
        return view('named.form', ['item' => $this->model::findOrFail($id), 'meta' => $this->meta()]);
    }

    public function update(NameRequest $request, string $id)
    {
        $this->model::findOrFail($id)->update($request->validated());

        return to_route("{$this->route}.index")->with('status', __('Saved.'));
    }

    public function destroy(string $id)
    {
        $item = $this->model::findOrFail($id);
        if ($reason = $item->deletionBlocker()) {
            return back()->with('error', $reason);
        }
        $item->delete();

        return back()->with('status', __('Deleted.'));
    }

    private function meta(): array
    {
        return [
            'route' => $this->route, 'title' => $this->title, 'singular' => $this->singular, 'description' => $this->description,
            'count_heading' => $this->countColumn[0], 'count_attribute' => $this->countColumn[1].'_count', 'has_active' => $this->hasActiveFlag,
        ];
    }
}
