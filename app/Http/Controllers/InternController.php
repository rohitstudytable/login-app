<?php

namespace App\Http\Controllers;

use App\Models\Intern;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        $stringAlphanumeric = Str::random(10);


        // ✅ save to DB
        $validated['random_id'] = $stringAlphanumeric;

        $intern = Intern::create($validated);
        $interncode = 'INT' . Carbon::now()->format('y')  . $intern->id;

        $intern->intern_code = $interncode;
        $intern->save();


        return redirect()->route('interns.index')
            ->with('success', 'Intern added successfully');
    }

    public function destroy(Intern $intern)
    {
        $intern->delete();
        return back()->with('success', 'Intern deleted');
    }
}
