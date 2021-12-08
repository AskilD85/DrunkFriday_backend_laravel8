<?php
namespace App\Http\Controllers;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

use Illuminate\Contracts\Filesystem\Filesystem;

use App\Services\FileService;
use Illuminate\Support\Facades\Auth;




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
        //return Article::all();
        //$article = User::orderBy('updated_at', 'desc')->get();
        //return  response()->json($article, 200);
        $user = User::select('name', 'email', 'type', 'id', 'desc', 'created_at', 'updated_at')
        ->where('type', '!=', 'admin')
        ->orderBy('created_at', 'desc')
        ->get();

         return response()->json($user , 200);
    }

    public function show($user)
    {
        
        $users = User::select('name', 'email', 'type', 'id','desc','created_at', 'updated_at')->where('id', $user)->get();
       // $users = User::find($user);
        
        return response()->json($users[0], 200);
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

        //Валидация
        $this->validate($request,[
            'id'    => 'required|string|integer|exists:users',
        ]);
		if($request->hasFile('ava')) {
        	$file = $request->file('ava');
        	$filename = uniqid() . '.' . $file->extension();
        	$file = Storage::put('avatars/'.$request->id, $file);
        }

        $user = User::FindOrFail($request->id);
	
	    $user->update($request->only('name', 'email', 'password', 'type', 'ava','desc','phone'));
	    
        return response()->json($user, 200);
    }
    
    
    public function getFile(Request $request) {
    	$file = File::where('id','=', $file->id)->firstOrFail();
    	$path = $this->filesystem->path($file->name);
        return response()->download($path);
    }



    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(null, 204);
    }
    
    public function user($id) {
    	$user = User::all()->where('id', $id);
    	return response()->json($user, 200);
    }
}