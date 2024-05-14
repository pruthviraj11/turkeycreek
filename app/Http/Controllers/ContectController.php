<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contect;

class ContectController extends Controller
{

    public function destroy(Request $request, $id)
    {
        $testimonial = Contect::findOrFail($id);
        $testimonial->delete();

        return redirect()->route('contact.list')
            ->with('success', 'Contact deleted successfully');
    }

    public function getall(Request $request)
    {
        $Contacts = Contect::orderBy('id', 'DESC')->get();
        return view('users/Contact/list', compact('Contacts'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        Contect::create($validatedData);


        return redirect(route('home'))->with('success', 'Message sent successfully');
    }
}
