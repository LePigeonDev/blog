<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    public function show()
    {
        return view('pages.contact');
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required','string','max:120'],
            'email'   => ['required','email','max:255'],
            'subject' => ['nullable','string','max:180'],
            'message' => ['required','string','min:10','max:5000'],
        ]);

        $data['ip'] = $request->ip();
        ContactMessage::create($data);

        return back()->with('success','Merci pour votre message, nous vous répondrons rapidement.');
    }
}
