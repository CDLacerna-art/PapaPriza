<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Handle the contact / order-enquiry form submission.
     *
     * Validates the posted fields and redirects back with a success flash
     * message. No records are stored in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit(Request $request): RedirectResponse
    {
        $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        return redirect()
            ->route('contact.show')
            ->with('success', 'Thank you, ' . e($request->input('name')) . '! Your message has been received. We will get back to you soon.');
    }
}
