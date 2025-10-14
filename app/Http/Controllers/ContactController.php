<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show()
    {
        $page = Page::firstWhere('slug','contact');
        return view('pages.contact', compact('page'));
    }
}

