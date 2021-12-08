<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;


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

	public function login(Request $request)
	{
		
	



	    $this->validateLogin($request);
	    
	    
	    if ($this->attemptLogin($request)) {
	        
	        $auth = User::where('email', $request->email)
	        ->where('status', 10)
    		->get();
    		

	        if ($auth->count() > 0) {
	        	$user = $this->guard()->user();
	        	$user->generateToken();
	        	return response()->json([
	            'data' => $user, 200
	        ]);	
	        
	        } else {
		        return response()->json([
		            'result' => 'noVerifyEmail',
		            'text' => "Ваш Email не подтверждён"
		        ]);	
	        }
	        
	        
	    }
	    return response()->json([
		            'result' => 'error',
		            'text' => "Неверный логин или пароль!"
		        ]);	
}


	public function checkToken(Request $request) {
		/*
		$checktoken = Auth::guard('api')->check();
		return response()->json($checktoken, 200);
*/

		$this->validate($request, [
            'token' => 'string',
            'verify_token' => 'string',
            ]);
            
            
		// $token = User::where('verify_token','=', $request->token)->first();
		$token = User::where('api_token','=', $request->token)->first();
		
		if (!$token) {
			return response()->json(['result'=>'error', 'text'=> 'Токен не действителен!']);
		}
		return response()->json(['result'=>'OK']);
		
		
	}


	public function logout()
	{ 
	    if (Auth::check()) {
	       Auth::user()->AauthAcessToken()->delete();
	    }
	    return [
			'status' => 'success',
			'message' => 'Logout successfully.'
			];
	}

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home2';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
 
}
