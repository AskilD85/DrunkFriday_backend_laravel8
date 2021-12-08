<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\User;
use Mail;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Hash;


class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;
    
    public function forgetpass (Request $request) {
    	
    	$user = User::where('email', $request->email)->first();
    	
    	if (!$user) {
    		return response()->json(['result'=>'error', 'text'=> 'Такой email не найден!']);
    	}
    	
    	$user->status = User::STATUS_INACTIVE;
    	$user->verify_token = str_random(60);
    	$user->save();
    	$data = array(
    		'token'	=> $user->verify_token,
	    	'name' => 'name',
	    	'url'	=> 'http://master702.ru/#/resetPassw/'
	    	);
	    	
       
			Mail::send(['html'=>'mail/resetpassword'], $data, function($message) use ($user) {
        	$message->to($user->email)
	    			->subject('Восстановление пароля!');
	        $message->from('info@master702.ru');
	    	});	
		

    	return response()->json([
    		'result'=> 'OK',
    		'text' => 'Письмо отправлено на ваш E-mail',
    		'user'=> $user
    	]);
    }

	public function  checkToken(Request $request) 
	{	
		$this->validate($request, [
            'token' => 'string',
            'verify_token' => 'string',
            ]);
            
            
		// $token = User::where('verify_token','=', $request->token)->first();
		$token = User::where('api_token','=', $request->token)->first();
		
		if (!$token) {
			return response()->json(['result'=>'error', 'text'=> 'Ссылка больше не работает!']);
		}
		return response()->json(['result'=>'OK']);
	}
	
	
	public function saveNewPass(Request $request) 
	{
		$user = User::where('verify_token', $request->token)->first();
		if (!$user) {
			return response()->json(['result'=>'error', 'text'=> 'Токен не валидный']); 
		}
		
		$user->verify_token = null;
		$user->password = Hash::make($request->password);
		$user->status = User::STATUS_ACTIVE;
		$user->save();
		
		return response()->json(['result'=>'OK', 'text'=> 'Пароль обновлён!']); 
	}
    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
}
