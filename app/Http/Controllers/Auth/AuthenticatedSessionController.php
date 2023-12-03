<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\reg_employee_mst;
use App\Models\api_logins_mst;
use App\Models\website_profile;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Session;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        
        $request->validate([
            'nrc' => 'regex:/^\d{6}\/\d{2}\/\d{1}$/',
            'password' => 'required|string'
        ], [
            'regex' => 'Please enter a valid NRC with slashes. Example: 123456/78/9',
        ]);
        
        

        $credentials = request(['nrc', 'password']);
        $credentials['nrc'] = str_replace('/', '', $credentials['nrc']);
        if (Auth::guard('employees')->attempt($credentials)) {
            $user = reg_employee_mst::where('nrc', "=", str_replace('/','',$request->nrc))->firstOrFail();
            
            Auth::login($user);        

            if ($user->hasAnyRole()) {
                return redirect()->intended(RouteServiceProvider::ADMIN);
            }
           
            
            else {   

                //Log In the user to the Clients Dashboard now
                return redirect()->intended(RouteServiceProvider::HOME);
            }
        }

//Else if the login failed
        //Throw error for wrong credentials
        throw ValidationException::withMessages(['These credentials do not match our records']);
    }



    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        Session::flash('status','Welcome!!!. Login to check your Profile');
        return redirect('login');
    }
}
