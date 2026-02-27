<?php

namespace App\Http\Controllers;

use App\Models\Intern;
use Illuminate\Http\Request;

class AllholiController extends Controller
{
    public function index()
    {
        $interns = Intern::orderBy('name')->get();

        $cities = Intern::whereNotNull('city')
            ->distinct()
            ->pluck('city');

        return view('interns.all_holi', compact('interns', 'cities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'holidays'  => 'required|integer|min:1',
            'intern_id' => 'nullable|exists:interns,id',
            'city'      => 'nullable|string',
        ]);

        if ($request->filled('intern_id')) {
            Intern::findOrFail($request->intern_id)
                ->increment('total_holidays', $request->holidays);

            return back()->with('success', 'Holidays added');
        }

        if ($request->filled('city')) {
            Intern::where('city', $request->city)
                ->increment('total_holidays', $request->holidays);

            return back()->with('success', 'Holidays added by city');
        }

        return back()->withErrors('Select intern or city');
    }
}