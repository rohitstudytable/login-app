<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HolidayController extends Controller
{
    /**
     * Display list of holidays
     */
    public function index()
    {
        $holidays = Holiday::orderBy('holiday_date', 'asc')->get();
        return view('admin.holidays.index', compact('holidays'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.holidays.create');
    }

    /**
     * Store new holiday
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'holiday_date' => 'required|date|unique:holidays,holiday_date'
        ]);

        $validated['holiday_date'] = Carbon::parse($validated['holiday_date'])->format('Y-m-d');

        Holiday::create($validated);

        return redirect()
            ->route('admin.holidays.index')
            ->with('success', 'Holiday added successfully');
    }

    /**
     * Show edit form
     */
    public function edit(Holiday $holiday)
    {
        return view('admin.holidays.edit', compact('holiday'));
    }

    /**
     * Update holiday
     */
    public function update(Request $request, Holiday $holiday)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'holiday_date' => 'required|date|unique:holidays,holiday_date,' . $holiday->id
        ]);

        $validated['holiday_date'] = Carbon::parse($validated['holiday_date'])->format('Y-m-d');

        $holiday->update($validated);

        return redirect()
            ->route('admin.holidays.index')
            ->with('success', 'Holiday updated successfully');
    }

    /**
     * Delete holiday
     */
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return redirect()
            ->route('admin.holidays.index')
            ->with('success', 'Holiday removed successfully');
    }
}
