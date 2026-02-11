<?php

namespace App\Http\Controllers;

use App\Models\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class InternController extends Controller
{
    /**
     * Only admin (web guard) can manage interns
     */
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    /**
     * List interns / employees
     */
    public function index(Request $request)
    {
        $query = Intern::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%")
                  ->orWhere('intern_code', 'like', "%{$search}%");
            });
        }

        $interns = $query->latest()->paginate(10);

        return view('interns.index', compact('interns'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('interns.create');
    }

    /**
     * Store Intern / Employee
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:interns,email',
            'contact'     => 'nullable|string|max:20',
            'role'        => 'required|in:intern,employee',
            'intern_code' => 'required|string|unique:interns,intern_code',
        ]);

        // Create intern/employee
        $intern = Intern::create([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'contact'     => $validated['contact'] ?? null,
            'role'        => $validated['role'],
            'intern_code' => $validated['intern_code'],
            'random_id'   => Str::random(10),
        ]);

        // Generate password automatically
        $year   = date('y');
        $prefix = $intern->role === 'employee' ? 'EMP' : 'INT';
        $plainPassword = $prefix . $year . $intern->id;

        $intern->update([
            'plain_password' => $plainPassword, // optional (remove in production)
            'password'       => Hash::make($plainPassword),
        ]);

        return redirect()
            ->route('interns.index')
            ->with('success', ucfirst($intern->role) . " created successfully. Password: {$plainPassword}");
    }

    /**
     * Edit intern / employee
     */
    public function edit(Intern $intern)
    {
        return view('interns.edit', compact('intern'));
    }

    /**
     * Update intern / employee
     */
    public function update(Request $request, Intern $intern)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:interns,email,' . $intern->id,
            'contact'     => 'nullable|string|max:20',
            'role'        => 'required|in:intern,employee',
            'intern_code' => 'required|string|unique:interns,intern_code,' . $intern->id,
        ]);

        $intern->update($validated);

        return redirect()
            ->route('interns.index')
            ->with('success', 'Record updated successfully');
    }

    /**
     * Delete intern / employee
     */
    public function destroy(Intern $intern)
    {
        $intern->delete();

        return redirect()
            ->route('interns.index')
            ->with('success', 'Record deleted successfully');
    }
}
