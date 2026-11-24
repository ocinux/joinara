<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        // Owner yang sudah login langsung masuk panel; tamu baru melihat landing.
        if ($request->user()) {
            return redirect()->route('dashboard');
        }

        return view('welcome');
    }
}
