<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanelController extends Controller
{
    public function show()
    {
        return view('internal.panel', ['user' => Auth::user()]);
    }

    public function personal()
    {
        abort_unless(Auth::user() && in_array(Auth::user()->rol, ['tripulacion', 'admin'], true), 403);

        return view('internal.panel', ['user' => Auth::user()]);
    }
}
