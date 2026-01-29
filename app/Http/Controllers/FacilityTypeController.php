<?php

namespace App\Http\Controllers;

use App\Models\FacilityType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request as FacadesRequest;

class FacilityTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facilityTypes = FacilityType::when(FacadesRequest::input('search'), function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString()
            ->onEachSide(0);

        return Inertia::render('FacilityTypes/Index', [
            'facilityTypes' => $facilityTypes,
            'filters' => FacadesRequest::only('search')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:facility_types,name',
            'is_active' => 'boolean',
        ]);

        FacilityType::create($validated);

        return redirect()->route('facility-types.index')
            ->with('success', 'Facility type created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FacilityType $facilityType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:facility_types,name,' . $facilityType->id,
            'is_active' => 'boolean',
        ]);

        $facilityType->update($validated);

        return redirect()->route('facility-types.index')
            ->with('success', 'Facility type updated successfully.');
    }
}
