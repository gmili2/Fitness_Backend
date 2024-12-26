<?php

namespace App\Http\Controllers;

use App\Models\UserAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserAdminAuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('user_admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Les identifiants sont incorrects.',
        ]);
    }

    public function showRegisterForm()
    {
        return view('admin.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:user_admins',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Créer un nouvel administrateur
        UserAdmin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Connexion automatique après l'inscription
        Auth::guard('user_admin')->attempt($request->only('email', 'password'));

        // Rediriger vers le tableau de bord
        return redirect()->route('admin.dashboard')->with('success', 'Inscription réussie !');
    }

    public function logout()
    {
        Auth::guard('user_admin')->logout();
        return redirect()->route('admin.login');
    }


}
