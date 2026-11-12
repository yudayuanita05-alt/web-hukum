<?php

namespace App\Http\Controllers;

use App\Models\Klasterisasi;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $klasterisasi = Klasterisasi::all();
        $advokat = User::where('id_role', 2)->where('is_active', 1)->get();

        return view('frontend.home', compact('klasterisasi', 'advokat'));
    }
}

