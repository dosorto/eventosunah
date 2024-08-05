<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Jetstream;
use App\Models\User;
use App\Models\Persona;
use App\Models\Nacionalidad;
use App\Models\Tipoperfil;
use Illuminate\Support\Facades\Auth;

class RegistrarUsarioController extends Controller
{


    //

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => $this->passwordRules(),
        ]);
        $user = new User([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        return $this->storePersona($user);
    }

    public function storePersona(User $user)
    {
        // dd($user->password);
        $nacionalidades = Nacionalidad::all();
        $tipoperfiles = Tipoperfil::all();
        return view('auth.nueva-persona', ['user' => $user, 'password' => $user->password,  'nacionalidades' => $nacionalidades, 'tipoperfiles' => $tipoperfiles]);
    }

    public function passwordRules()
    {
        return [
            'required',
            'string',
            'min:8',
            'confirmed'
        ];
    }

    public function registrarPersona(Request $request)
    {
        $userData = json_decode($request->User, true);

        // Crear una nueva instancia del modelo User con los datos decodificados
        $user = new User($userData);
        $user->password = $request->UserC;
        $user->save();

        $user->roles()->attach(2);
        
        $persona = new Persona();
        $persona->IdUsuario = $user->id;
        $persona->dni = $request->dni;
        $persona->nombre = $request->nombre;
        $persona->apellido = $request->apellido;
        $persona->correo = $request->correo;
        $persona->correoInstitucional = $request->correoInstitucional;
        $persona->fechaNacimiento = $request->fechaNacimiento;
        $persona->sexo = $request->sexo;
        $persona->direccion = $request->direccion;
        $persona->telefono = $request->telefono;
        $persona->numeroCuenta = $request->numeroCuenta;
        $persona->IdNacionalidad = $request->IdNacionalidad;
        $persona->IdTipoPerfil = $request->IdTipoPerfil;
        $persona->created_by = $user->id;
        // correo institucional y numero de cuenta son opcionales, registrar si trae
        $persona->correoInstitucional = $request->correo_institucional;
        $persona->numeroCuenta = $request->cuenta_estudiante;
        $persona->save();

        // iniciar sesion al usuario actual
        Auth::login($user);

        return redirect()->route('eventoVista');
    }





}
