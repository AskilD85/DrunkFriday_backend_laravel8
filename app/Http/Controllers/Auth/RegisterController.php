<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Mail\Auth\VerifyMail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
    	
    	$user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'verify_token' => Str::random(60),
            'status' =>  User::STATUS_INACTIVE,
        ]);
        
    	//Mail::to($user->email)->send(new VerifyMail($user));
    	
    $data = array(
    		'token'	=> $user->verify_token,
	    	'name' => 'name'
	    	);
	    	
       
			Mail::send(['html'=>'mail/verifyEmail'], $data, function($message) use ($user) {
        	$message->to($user->email)
	    			->subject('Подтверждение почты');
	        $message->from('info@master702.ru');
	    	});

    	return $user;
    }
    
    
   protected function sendVerifyEmail(Request $request) {
   		$user = User::where('email', $request->email)->first();
   		$user->verify_token = str_random(60);
		$user->save();
   		
   		$data = array(
    		'token'	=> $user->verify_token,
	    	'name' => $user->name
	    	);
   	
   		Mail::send(['html'=>'mail/verifyEmail'], $data, function($message) use ($user) {
        	$message->to($user->email)
	    			->subject('Подтверждение почты');
	        $message->from('info@master702.ru');
	    	});
	    return response()->json([
	    	'result' => 'OK'
	    	]);
   }
    
    
    
	public function register(Request $request)
	{
	    $this->validator($request->all())->validate();
	    event(new Registered($user = $this->create($request->all())));
	
		// отправка админу уведомления о добавлении пользователя
		/*	$data = array(
        			'name' => $request->input('name'),
        			'email' =>$request->input('email'),
        			'user_id'	=> $user->id,
        		);
        	Mail::send(['html'=>'mail/register'], $data, function($message) {
        	$message->to('askildar@yandex.ru')
	    			->subject('Зарегистрирован новый пользователь!');
	        $message->from('info@master702.ru');
	    	});	*/
	$user = User::where('email', $request->email)->first();
	
	return response()->json([
	  'result' => 'OK',
	  'text' => 'Проверьте почту для подтверждения регистрации'
	]);	    
	   
	}
	
	 protected function registered(Request $request, $user)
    {
        $user->generateToken();

        return response()->json([$user->toArray()], 201);
    }
}
