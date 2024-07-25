<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Jetstream;
use App\Models\User;

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
       


        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        return redirect()->route('register');
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





}
