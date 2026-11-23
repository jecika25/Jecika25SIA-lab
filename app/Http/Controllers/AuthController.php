<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
		    return redirect()->route('dashboard.index');
		}
    }

    public function login(Request $request)
    {
        //Cek validasi input form login
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        //Cek apakah $credentials tersedia di database
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            //set session
            $request->session()->regenerate();

            if (Auth::attempt($credentials)) {
               $request->session()->regenerate();

            // Data tambahan ini terpisah dari status autentikasi
                session(['last_login' => now()]);
            } 
            //diarahkan ke halaman dashboard
            return redirect()->intended(route('dashboard.index'))
                ->with('success', 'Login berhasil!');
        }

        //$user = User::where('email',$request->email)->first();
        //if ($user && Hash::check($request->password, $user->password)) {
				/** sesssion mulai dibuat **/
		//    Auth::login($user);
		//    $request->session()->regenerate();
		
		//    return redirect()->intended(route('dashboard'));
		//}

        return back()
            ->withErrors(['email' => 'Email atau password salah'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
    }
}