<?php

namespace App\Http\Controllers;

use App\Models\Intern;
use Illuminate\Http\Request;

class InternController extends Controller
{
    public function index()
    {
        $interns = Intern::latest()->get();
        return view('interns.index', compact('interns'));
    }

    public function create()
    {
        return view('interns.create');
    }

    public function store(Request $request)
    {
        // ✅ validate first
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:interns,email',
        ]);

        // ✅ save to DB
        Intern::create($validated);

        return redirect()->route('interns.index')
            ->with('success', 'Intern added successfully');
    }

    public function destroy(Intern $intern)
    {
        $intern->delete();
        return back()->with('success', 'Intern deleted');
    }
}
