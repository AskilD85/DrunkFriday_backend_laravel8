<?php
namespace App\Http\Controllers;
use App\User;
use App\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

use Illuminate\Contracts\Filesystem\Filesystem;

use App\Services\FileService;
use Illuminate\Support\Facades\Auth;
use App\Resources\UsersResource;
use App\Resources\UserResource;


class UserController extends Controller
{
	/**
     * @var FileService
     */
    private $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }
	
    public function index()
    {
        $users = User::select('name', 'email', 'type', 'id', 'status','desc', 'created_at', 'updated_at', 'ava')
        ->where('type', '!=', 'admin')
        ->orderBy('created_at', 'desc')
        ->get();

        return new UsersResource(
            $users
        );
        
         return response()->json($users , 200);
    }
    




    public function show($user_id)
    {

        // $user = User::select('name', 'email', 'type', 'id','ava','desc','created_at', 'updated_at')->where('id', $user_id)->get();
        $user = User::find($user_id);
         return new UserResource($user);
        
        // return response()->json($users[0], 200);
    }





    public function store(Request $request)
    {
        $user = User::create($request->all());
        return response()->json($user, 200);
    }
    
    
    
    public function add(Request $request)
    {
        $user = User::create($request->all());
        return response()->json($user, 200);
    }



	// Обновить данные пользователя
    public function update(Request $request)
    {
    	$user = Auth::user();

       //Валидация
        $this->validate($request,[
            'id'    => 'required|string|integer|exists:users',
        ]);
     
	//если есть картинка
        if($request->hasFile('avatar') && $request->file('avatar')->isValid()){
           $media = $user->addMediaFromRequest('avatar')->toMediaCollection('avatars');
        }
		
		$request['ava'] =$media->id;
	    $user->update($request->only('name', 'email', 'password','ava', 'type','desc','phone'));
		$user = User::select('name', 'email', 'type','ava','id','desc','created_at', 'updated_at')->where('id', $user->id)->get();

	    return new UsersResource(
            $user
        );
    }
    
    
    
    public function getFile(Request $request)
    {
    	$file = File::where('id','=', $file->id)->firstOrFail();
    	$path = $this->filesystem->path($file->name);
        return response()->download($path);
    }



    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(null, 204);
    }
    
    public function user($id)
    {
    	$user = User::all()->where('id', $id);
    	return response()->json($user, 200);
    }
}