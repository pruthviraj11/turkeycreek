<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GuestUser;

class GuestUserController extends Controller
{

    public function destroy(Request $request, $id)
    {
        $guestuser = GuestUser::findOrFail($id);
        $guestuser->delete();

        return redirect()->route('guest_user.list')
            ->with('success', 'Guest User deleted successfully');
    }

    public function index()
    {
        $GuestUsers = GuestUser::all();
        return view('users/GuestUser/list', compact('GuestUsers'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'organization' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'email' => 'required|email|unique:guest_users',
            'address' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        $contact = new GuestUser();
        $contact->organization = $validatedData['organization'];
        $contact->contact_person = $validatedData['contact_person'];
        $contact->phone_number = $validatedData['phone_number'];
        $contact->email = $validatedData['email'];
        $contact->address = $validatedData['address'];
        $contact->location = $validatedData['location'];
        $contact->save();
        return redirect(route('home'))->with('success', 'Guest User sent successfully');
    }
}
