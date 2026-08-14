<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated($request, $user)
    {
        if ($user->hasAnyRole(['participante', 'conferencista', 'super-admin', 'admin-eventos', 'gestor-contenido'])) {
            return redirect()->route('dashboard');
        }

        if ($user->roles->isEmpty()) {
            auth()->logout();

            return redirect('/login')->with('error', 'No tiene ningún rol asignado.');
        }

        return redirect()->intended($this->redirectPath());
    }


}
