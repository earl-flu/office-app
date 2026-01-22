<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request as FacadesRequest;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programs = Program::when(FacadesRequest::input('search'), function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('abbreviation', 'like', "%{$search}%");
        })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString()
            ->onEachSide(0);

        return Inertia::render('Programs/Index', [
            'programs' => $programs,
            'filters' => FacadesRequest::only('search')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Programs/Create', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        Program::create($validated);

        return redirect()->route('programs.index')
            ->with('success', 'Program created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Program $program)
    {
        return Inertia::render('Programs/Edit', [
            'program' => $program,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $program->update($validated);

        return redirect()->route('programs.index')
            ->with('success', 'Program updated successfully.');
    }
}
