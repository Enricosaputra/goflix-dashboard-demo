<?php

namespace App\Http\Controllers;

use App\Enums\PersonRole;
use App\Http\Requests\PersonRequest;
use App\Models\Person;

class PersonController extends Controller
{
    public function index()
    {
        $people = Person::withCount('movies')
            ->when(request('role'), fn ($q, $role) => $q->where('role', $role))
            ->when(request('q'), fn ($q, $term) => $q->whereRaw('lower(name) like ?', ['%'.mb_strtolower($term).'%']))
            ->orderBy('name')->paginate(20)->withQueryString();

        return view('people.index', ['people' => $people, 'roles' => PersonRole::cases()]);
    }

    public function create()
    {
        return view('people.form', ['person' => new Person(['is_active' => true]), 'roles' => PersonRole::cases()]);
    }

    public function store(PersonRequest $request)
    {
        Person::create($request->validated());

        return to_route('people.index')->with('status', __('Saved.'));
    }

    public function edit(Person $person)
    {
        return view('people.form', ['person' => $person, 'roles' => PersonRole::cases()]);
    }

    public function update(PersonRequest $request, Person $person)
    {
        $person->update($request->validated());

        return to_route('people.index')->with('status', __('Saved.'));
    }

    public function destroy(Person $person)
    {
        if ($reason = $person->deletionBlocker()) {
            return back()->with('error', $reason);
        }
        $person->delete();

        return back()->with('status', __('Deleted.'));
    }
}
