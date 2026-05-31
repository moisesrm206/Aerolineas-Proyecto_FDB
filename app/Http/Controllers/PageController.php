<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function home()
    {
        if (Auth::check()) {
            return redirect()->route('panel.principal');
        }

        return view('public.home');
    }

    public function equipaje()
    {
        return view('client.equipaje');
    }
}
