<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * Muestra la vista de Login.
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Muestra la vista de Registro.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Procesa el Registro de un nuevo usuario.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        //Autenticar automáticamente
        Auth::login($user);

        //Regenerar la sesión por seguridad
        $request->session()->regenerate();

        return redirect()->route('search')->with('success', '¡Registro exitoso!');
    }

    /**
     * Procesa el Inicio de Sesión
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Intentar autenticar con Auth::attempt
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Regenerar sesión para prevenir ataques de fijación
            $request->session()->regenerate();

            return redirect()->intended(route('search'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Cierra la sesión del usuario (Logout).
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index');
    }
}
