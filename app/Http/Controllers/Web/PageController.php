<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function jeu()
    {
        return view('pages.jeu');
    }

    public function gain()
    {
        return view('pages.gain');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function sendContact(Request $request)
    {
        $request->validate([
            'last_name'  => ['required', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email'],
            'subject'    => ['required', 'string', 'max:200'],
            'message'    => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'last_name.required'  => 'Le nom est obligatoire.',
            'first_name.required' => 'Le prénom est obligatoire.',
            'email.required'      => 'L\'adresse e-mail est obligatoire.',
            'subject.required'    => 'Le sujet est obligatoire.',
            'message.required'    => 'Le message est obligatoire.',
            'message.min'         => 'Le message doit contenir au moins 10 caractères.',
        ]);

        // TODO: envoyer l'email (Mail::to(...)->send(...))
        // Pour l'instant on stocke juste la session success

        return back()->with('success', 'Votre message a bien été envoyé ! Nous vous répondrons sous 48h.');
    }

    public function politique()
    {
        return view('pages.politique');
    }

    public function cgv()
    {
        return view('pages.cgv');
    }

    public function cgu()
    {
        return view('pages.cgu');
    }
}