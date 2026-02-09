<?php

namespace App\Http\Controllers;

use App\Models\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InternController extends Controller
{
    /**
     * Display a listing.
     */
    public function index(Request $request)
    {
        $query = Intern::query();

        // 🔹 FILTER BY ROLE (FOR TABS)
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // 🔹 SEARCH
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('contact', 'like', "%{$request->search}%")
                  ->orWhere('role', 'like', "%{$request->search}%");
            });
        }

        $interns = $query->latest()->get();

        return view('interns.index', compact('interns'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('interns.create');
    }

    /**
     * Store Intern / Employee.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:interns,email',
            'contact' => 'nullable|string|max:20',
            'role'    => 'required|in:intern,employee',
        ]);

        // Generate random_id
        $validated['random_id'] = Str::random(10);

        // Create record
        $user = Intern::create($validated);

        // Code prefix based on role
        $prefix = $user->role === 'employee' ? 'EMP' : 'INT';

        // Generate intern_code
        $user->intern_code = $prefix . Carbon::now()->format('y') . $user->id;
        $user->save();

        return redirect()->route('interns.index')
                         ->with('success', ucfirst($user->role) . ' added successfully');
    }

    /**
     * Edit.
     */
    public function edit(Intern $intern)
    {
        return view('interns.edit', compact('intern'));
    }

    /**
     * Update.
     */
    public function update(Request $request, Intern $intern)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:interns,email,' . $intern->id,
            'contact' => 'nullable|string|max:20',
            'role'    => 'required|in:intern,employee,admin',
        ]);

        $intern->update($validated);

        return redirect()->route('interns.index')
                         ->with('success', 'Record updated successfully');
    }

    /**
     * Delete.
     */
    public function destroy(Intern $intern)
    {
        $intern->delete();

        return back()->with('success', 'Record deleted successfully');
    }
}
