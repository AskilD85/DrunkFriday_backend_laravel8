<?php
namespace App\Modules\File;

use App\Article;
use App\Category;
use App\Comment;
use App\File;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mail;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\FileService;


class FileController extends Controller
{
	/**
     * @var FileService
     */
    private $fileService;
    
     public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }
    
    
    public function index(Request $request, $city_id)
    {
    	
       $article = Article::where([
       	['articles.active', '1'],
       	['city_id','=',$city_id]
       	])
       ->join('categories', 'categories.id','=', 'articles.category_id')
       ->select('articles.*', 'categories.name as category_name')
       ->orderBy('updated_at', 'desc')
       ->get();
       if ($article->count() !== 0) {
       		return response()->json($article, 200);
       } else {
       	return response()->json([]);
       }
    	
    }

    public function show(Request $media)
    {
    	$media = new File;
    	
        $media->id = $yourModel->getMedia();
        return response()->json($article, 200);
    }
    
    
    

    public function store(Request $request)
    {
        $article = Article::create($request->all());
        
        $article_info = Article::select('title', 'body', 'user_id', 'category_id', 'active', 'type','id','city_id')
        	->where('id', $article->id)
        	->get();
        	
    	$user = User::select('name')->where('id',$article_info[0]->user_id )->get();
        $categ = Category::select('name')->where('id',$article_info[0]->category_id)->get();
        
        $data = array(
    		'link_id'	=> $article_info[0]->id,
	    	'title'		=> $article_info[0]->title,
	    	'text'		=> $article_info[0]->body,
	    	'type'		=> $article_info[0]->type,
	    	'username'	=> $user[0]->name,
	    	'categ_name'=> $categ[0]->name,
	    	'user_id'	=> $article_info[0]->user_id,
	    	'city_id'	=> $article_info[0]->city_id,
	    	);


        if($request->hasFile('myfile')) {
            $file = $request->file('myfile');
            $file->move(storage_path().'/images', $article_info[0]->user_id.'_myfile.img');
            return response()->json('{"ok":"ok"}');
        }
        
     

       /* if ($article) {
			Mail::send(['html'=>'mail/addArticle'], $data, function($message) {
        	$message->to('askildar@yandex.ru')
	    			->subject('Добавлен новый пост!');
	        $message->from('info@master702.ru');
	    	});	
		}*/
		
        return response()->json($data, Response::HTTP_OK);
    }
    
   
     public function add(Request $request)
    {
        $article = Article::create($request->all());
        return response()->json($article, 200);
    }
    
    
    
    
  
  
    

 public function uploadFile(Request $request)
    {
    	return response()->json('333');
    	
        return new FileResource(
            $this->fileService->upload(
                $request->file('file'),
                Auth::user()
            )
        );
    }
    
    
    
    
 public function getFile(Request $request) {



	$exists = Storage::disk('public')->exists('file.jpg');

    if(!$request->hasFile('image')) {
        return response()->json(['upload_file_not_found'], 400);
    }
    
    $file = $request->file('image');
   

    if(!$file->isValid()) {
        return response()->json(['invalid_file_upload'], 400);
    }
    
    // $path = public_path() . '/storage/images/';
    $path = Storage::putFileAs('avatars/'.auth('api')->user()->id, $request->file('image'), $file->getClientOriginalName() );
    // $file->move($path, $file->getClientOriginalName());
    return response()->json('/storage/app/'.$path);
 }
 
 
    
}


