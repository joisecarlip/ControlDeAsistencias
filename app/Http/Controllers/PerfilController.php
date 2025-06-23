<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    public function show()
    {
        $user = Auth::guard('usuarios')->user();
        return view('perfil', compact('user'));
    }
}
