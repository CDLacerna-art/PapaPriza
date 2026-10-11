<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Handle the contact form submission
    public function submit(Request $request): RedirectResponse
    {

     // Check if the form inputs are valid
        $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        // Return to the contact page with a success message
        return redirect()
            ->route('contact.show')
            ->with('success', 'Thank you, ' . e($request->input('name')) . '! Your message has been received. We will get back to you soon.');
    }
}
