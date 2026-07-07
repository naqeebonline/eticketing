<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CityController extends Controller
{
    public function index(): View
    {
        $cities = City::query()->ordered()->paginate(20);

        return view('admin.cities.index', compact('cities'));
    }

    public function create(): View
    {
        return view('admin.cities.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:cities,name'],
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        City::create([
            'name' => trim($validated['name']),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.cities.index')->with('success', 'City added.');
    }

    public function edit(City $city): View
    {
        return view('admin.cities.edit', compact('city'));
    }

    public function update(Request $request, City $city): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('cities', 'name')->ignore($city->id)],
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $city->update([
            'name' => trim($validated['name']),
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.cities.index')->with('success', 'City updated.');
    }

    public function destroy(City $city): RedirectResponse
    {
        $city->delete();

        return redirect()->route('admin.cities.index')->with('success', 'City removed.');
    }
}
