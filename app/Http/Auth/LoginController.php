<?php

namespace App\Http\Controllers\Auth;
use App\Models\Contribuyente;
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
    protected $redirectTo = '/congreso';

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
    if ($user->hasRole('Usuario')) {
        // Obtener el perfil del contribuyente correspondiente al correo electrónico del usuario
        $contribuyente = Contribuyente::where('email', $user->email)->first();



        if ($contribuyente) {
             // Establecer la cookie 'contribuyente_id' con el nuevo ID
             cookie()->queue('contribuyenteH_id', $contribuyente->id);
             return redirect()->route('contribuyente.showHistory')->with('id', $contribuyente->id);

            // Redirigir al perfil del contribuyente en el historial
            //return redirect()->route('contribuyente.showHistory', ['id' => $contribuyente->id]);
        } else {
            // Si el perfil del contribuyente no existe, redirigir a una vista de error o a otra página apropiada
            return redirect('/congreso')->with('error', 'No se encontró el perfil del contribuyente.');
        }
    } elseif ($user->hasRole('root')) {
        return redirect()->route('/congreso/dashboard');
    } elseif ($user->roles->isEmpty()) {
        auth()->logout();
        return redirect('/login')->with('error', 'No tiene ningún rol asignado.');
    } else {
        return redirect()->intended($this->redirectPath());
    }
    }


}
