<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Category; // <-- AJOUT
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // On envoie la liste des catégories au formulaire d’inscription
        $categories = Category::orderBy('name')->get(['id','name']);
        return view('auth.register', compact('categories'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'    => ['required', 'confirmed', Rules\Password::defaults()],
            'categories'  => ['array'],                         // <-- AJOUT
            'categories.*'=> ['integer','exists:categories,id'],// <-- AJOUT
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user', // si tu utilises le champ 'role'
        ]);

        event(new Registered($user));
        Auth::login($user);

        // Sauvegarde des préférences
        if (method_exists($user, 'preferredCategories')) {
            $user->preferredCategories()->sync($request->input('categories', []));
        }

        // Redirection vers l’intention (article#comments) ou /account
        return redirect()->intended(route('account'));
    }
}
