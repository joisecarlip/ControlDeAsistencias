<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class LoginController extends Controller
{
    public function inicio()
    {
        $user = Auth::guard('usuarios')->user();

        switch ($user->rol) {
            case 'administrador':
                return view('admin.dashboard', compact('user'));
            case 'docente':
                return view('docente.dashboard', compact('user'));
            case 'estudiante':
            default:
                return view('estudiante.dashboard', compact('user'));
        }
    }

    public function MostrarFormularioLogin()
    {
        return view('auth.Login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required|string',
        ]);

        $credentials = $request->only('correo', 'contrasena');

        $user = Usuario::where('correo', $credentials['correo'])->first();

        if ($user && Hash::check($credentials['contrasena'], $user->contrasena)) {
            Auth::guard('usuarios')->login($user, $request->filled('remember'));
            $request->session()->regenerate();

            switch ($user->rol) {
                case 'administrador':
                    return redirect()->intended('/admin/inicio');
                case 'docente':
                    return redirect()->intended('/docente/inicio');
                case 'estudiante':
                default:
                    return redirect()->intended('/estudiante/inicio');
            }
        }

        return back()->withErrors([
            'correo' => 'Credenciales incorrectas.'
        ])->withInput($request->only('correo', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::guard('usuarios')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
