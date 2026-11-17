<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AuthController extends Controller
{
    public function login()
    {
        return view('modules.auth.login');
    }

    public function seleccionarRegistro()
    {
        return view('modules.auth.seleccionar-registro');
    }

    public function registroVoluntario()
    {
        return view('modules.auth.registro-voluntario');
    }

    public function registroOrganizacion()
    {
        return view('modules.auth.registro-organizacion');
    }

    public function logear(Request $request)
    {
        $credenciales = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credenciales)) {
            $request->session()->regenerate();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => '¡Sesión iniciada correctamente!',
                    'redirect' => route('home')
                ]);
            }

            return redirect('/home');
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Las credenciales ingresadas son incorrectas.'
            ], 401);
        }

        return back()->withErrors([
            'email' => 'Las credenciales son incorrectas.',
        ]);
    }

    public function registrar(Request $request)
    {
        $tipo = $request->input('tipo_usuario', 'estudiante');

        if ($tipo === 'organizacion') {
            // Si viene dividido en prefijo (20/10) + 9 dígitos, lo unimos automáticamente
            if ($request->filled('ruc_prefijo') && $request->filled('ruc_resto')) {
                $rucCompleto = preg_replace('/[^0-9]/', '', $request->input('ruc_prefijo') . $request->input('ruc_resto'));
                $request->merge(['ruc' => $rucCompleto]);
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'ruc' => ['required', 'regex:/^(10|20)[0-9]{9}$/', 'unique:users,ruc'],
                'distrito' => 'required|string'
            ], [
                'ruc.regex' => 'El RUC debe iniciar con 20 o 10 y tener exactamente 11 dígitos numéricos.',
                'ruc.unique' => 'Este número de RUC ya se encuentra registrado en el sistema.'
            ]);
        } else {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'dni' => ['required', 'regex:/^[0-9]{8}$/', 'unique:users,dni']
            ], [
                'dni.regex' => 'El DNI debe contener exactamente 8 dígitos numéricos.',
                'dni.unique' => 'Este número de DNI ya está registrado.'
            ]);
        }

        $datosUsuario = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tipo_usuario' => $tipo,
            'dni' => $request->dni,
            'telefono' => $request->telefono,
            'carrera' => $request->carrera,
            'disponibilidad' => $request->disponibilidad,
            'ruc' => $request->ruc,
            'distrito' => $request->distrito ?? 'Cusco Centro',
            'mision' => $request->mision,
        ];

        $user = User::create($datosUsuario);

        // Sincronizar columnas adicionales si existen en la tabla users
        $extra = [];
        if (Schema::hasColumn('users', 'rol')) {
            $extra['rol'] = $tipo === 'organizacion' ? 'organizacion' : 'voluntario';
        }
        if (Schema::hasColumn('users', 'estado_verificacion')) {
            $extra['estado_verificacion'] = $tipo === 'organizacion' ? 'Pendiente' : 'Aprobado';
        }
        if (!empty($extra)) {
            DB::table('users')->where('id', $user->id)->update($extra);
        }

        Auth::login($user);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => '¡Registro completado exitosamente!',
                'redirect' => route('home')
            ]);
        }

        return redirect('/home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}