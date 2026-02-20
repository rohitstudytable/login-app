<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * INTERN – UPDATE PROFILE IMAGE
     */
    public function updateImage(Request $request)
    {
        $request->validate([
            'img' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $intern = Auth::guard('intern')->user();

        // Delete old image
        if ($intern->img && Storage::disk('public')->exists($intern->img)) {
            Storage::disk('public')->delete($intern->img);
        }

        // Store new image
        $path = $request->file('img')->store('profile_images', 'public');

        $intern->update([
            'img' => $path,
        ]);

        return back()->with('success', 'Profile image updated successfully.');
    }

    /**
     * INTERN – UPDATE PERSONAL INFORMATION
     */
    public function updatePersonal(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'dob'             => 'nullable|date',
            'gender'          => 'nullable|in:male,female,other',
            'blood_group'     => 'nullable|string|max:5',
            'marital_status'  => 'nullable|in:single,married,divorced,widowed',
            'nationality'     => 'nullable|string|max:100',
        ]);

        $intern = Auth::guard('intern')->user();

        $intern->update([
            'name'            => $request->name,
            'dob'             => $request->dob,
            'gender'          => $request->gender,
            'blood_group'     => $request->blood_group,
            'marital_status'  => $request->marital_status,
            'nationality'     => $request->nationality,
        ]);

        return back()->with('success', 'Personal information updated successfully.');
    }

    /**
 * INTERN – UPDATE CONTACT & ADDRESS
 */
public function updateContact(Request $request)
{
    $intern = Auth::guard('intern')->user();

    $request->validate([
        'email'   => 'required|email|unique:interns,email,' . $intern->id,
        'contact' => 'nullable|digits_between:10,13',
        'address' => 'nullable|string',
        'city'    => 'nullable|string|max:100',
        'state'   => 'nullable|string|max:100',
        'pin'     => 'nullable|string|max:10',
    ]);

    $intern->update([
        'email'   => $request->email,
        'contact' => $request->contact,
        'address' => $request->address,
        'city'    => $request->city,
        'state'   => $request->state,
        'pin'     => $request->pin,
    ]);

    return back()->with('success', 'Contact details updated successfully.');
}

}
