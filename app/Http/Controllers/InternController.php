<?php

namespace App\Http\Controllers;

use App\Models\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InternController extends Controller
{
    /**
     * Display a listing of interns.
     */
    public function index(Request $request)
    {
        // Optional: add search/filter
        $query = Intern::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('contact', 'like', "%{$request->search}%");
        }

        $interns = $query->latest()->get();

        return view('interns.index', compact('interns'));
    }

    /**
     * Show the form for creating a new intern.
     */
    public function create()
    {
        return view('interns.create');
    }

    /**
     * Store a newly created intern in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:interns,email',
            'contact' => 'nullable|string|max:20',
        ]);

        // Generate random_id
        $validated['random_id'] = Str::random(10);

        // Set role as 'intern' by default
        $validated['role'] = 'intern';

        // Create intern
        $intern = Intern::create($validated);

        // Generate unique intern_code using ID and year
        $intern->intern_code = 'INT' . Carbon::now()->format('y') . $intern->id;
        $intern->save();

        return redirect()->route('interns.index')
                         ->with('success', 'Intern added successfully');
    }

    /**
     * Show the form for editing the specified intern.
     */
    public function edit(Intern $intern)
    {
        return view('interns.edit', compact('intern'));
    }

    /**
     * Update the specified intern in storage.
     */
    public function update(Request $request, Intern $intern)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:interns,email,' . $intern->id,
            'contact' => 'nullable|string|max:20',
            'role'    => 'required|string|in:intern', // keep role fixed
        ]);

        $intern->update($validated);

        return redirect()->route('interns.index')
                         ->with('success', 'Intern updated successfully');
    }

    /**
     * Remove the specified intern from storage.
     */
    public function destroy(Intern $intern)
    {
        $intern->delete();

        return back()->with('success', 'Intern deleted successfully');
    }
}
