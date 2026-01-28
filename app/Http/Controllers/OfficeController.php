<?php

namespace App\Http\Controllers;

use App\Models\FacilityType;
use App\Models\Office;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request as FacadesRequest;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offices = Office::orderBy('name')
            ->when(FacadesRequest::input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('facilityType', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });;
            })
            ->paginate(5)
            ->withQueryString()
            ->onEachSide(0);

        return Inertia::render('Offices/Index', [
            'offices' => $offices,
            'filters' => FacadesRequest::only('search')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // dd(FacilityType::orderBy('name')->get());
        return Inertia::render(
            'Offices/Create',
            ['facilityTypes' => FacilityType::orderBy('name')->get()]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:offices,name',
            'abbreviation' => 'nullable|string|max:255',
            'facility_type_id' => 'required|exists:facility_types,id',
        ]);

        Office::create($validated);
        return redirect()
            ->route('offices.index')
            ->with('success', 'Office created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Office $office)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Office $office)
    {
        return Inertia::render('Offices/Edit', [
            'facility' => $office->load('facilityType'),
            'office' => $office
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Office $office)
    {
        $validated = $request->validate([
            'name' => 'required',
            'abbreviation' => 'nullable|string|max:255',
            'facility_type_id' => 'required|exists:facility_types,id',
        ]);

        $office->update($validated);

        return redirect()->route('offices.index')
            ->with('success', 'Office updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Office $office)
    {
        //
    }
}
