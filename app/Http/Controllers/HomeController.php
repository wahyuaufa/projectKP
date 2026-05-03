<?php

namespace App\Http\Controllers;

use App\Models\Armada;
use App\Models\Rute;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $armadas = Armada::where('is_active', true)->get();
        $rutes   = Rute::where('is_active', true)->get();

        return view('home.index', compact('armadas', 'rutes'));
    }
}
