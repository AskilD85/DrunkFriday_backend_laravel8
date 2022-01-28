<?php
namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Comment;
use App\User;
use App\ArticleType;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mail;
use App\File;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Auth;
use App\Resources\ArticleResource;

use Illuminate\Support\Facades\Storage;


class ArticleController extends Controller
{
    public function index(Request $request, $city_id)
    {
    	
       $article = Article::where([
       	['articles.active', '1'],
       	['city_id','=',$city_id]
       	])
       ->join('categories', 'categories.id','=', 'articles.category_id')
       ->join('users', 'users.id','=', 'articles.user_id')
       ->join('article_types', 'article_types.id','=', 'articles.type')
       ->select('articles.title',
    			'articles.body', 
    			'categories.name as category_name',
    			'users.name as username',
    			'article_types.name as artycleType'
    			,'articles.updated_at'
    			,'articles.id',
    			'articles.type',
    			'articles.active',
    			'articles.category_id')
       ->orderBy('articles.updated_at', 'desc')
       ->get();
       if ($article->count() > 0) {
       	  return response()->json(ArticleResource::collection($article), Response::HTTP_OK);
       } else {
       	return response()->json([],Response::HTTP_OK);
       }
    	
    }

    public function show($article)
    {
        return response()->json($article, 200);
    }




    public function store(Request $request)
    {

    	 $this->validate($request,[
            'title' 	=> 'required',
            'body'		=> 'required',
            'city_id'	=> 'required',
            
        ]);
        $img_url = null;

        $article = Article::create( 
        	    ['title'	=>$request->title,
        	    'body'		=>$request->body,
        	    'user_id'	=>$request->user_id,
        	    'category_id'=>$request->category_id,
        	    'active'	=>$request->active,
        	    'type'		=>$request->type,
        	    'city_id'	=>$request->city_id
        	    ]
        	 );
        
     
    	$user = Auth::user();

        //если есть image
        if($request->hasFile('image') && $request->file('image')->isValid())
        {
           $article->addMediaFromRequest('image')->toMediaCollection('images');
           $img_url =  $article->getMedia('images')->last()->getUrl('thumb_200x200');
        }
        

        $data = array(
    		'link_id'	=> $article->id,
	    	'title'		=> $article->title,
	    	'body'		=> $article->body,
	    	'type'		=> $article->type,
	    	'username'	=> $user->name,
	    	'category_name'=> Category::find($request->category_id)->name,
	    	'user_id'	=> $user->id,
	    	'city_id'	=> $article->city_id,
	    	'created_at'=>$article->created_at,
	    	'updated_at'=>$article->updated_at,
	    	'img_url' => $img_url
	    	);
	    
  
       /* if ($article) {
			Mail::send(['html'=>'mail/addArticle'], $data, function($message) {
        	$message->to('askildar@yandex.ru')
	    			->subject('Добавлен новый пост!');
	        $message->from('info@master702.ru');
	    	});	
		}*/
		
      

        // return $yy->getFirstMediaUrl();
        return response()->json($data, Response::HTTP_OK);
    }
   
   
   
   
     public function add(Request $request)
    {
        $article = Article::create($request->all());
        return response()->json($article, 200);
    }
    
    
    
    
  
  
    
public function uploadFile(Request $request) {


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
    return response()->json('storage/app/'.$path);
 }
    
    
    
    
 public function getFile(Request $request) {



	$mediaItems = $yourModel->getMedia();
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
 
 
 
 
    
    
    
    public function myfile(Request $request)
    {



          if($request->hasFile('myfile')) {
            $file = $request->file('myfile');
           
           Storage::put('avatars/1', $file);


           return Storage::put('avatars/'.$request->user_id, $file);
           
           
            $file->move(storage_path().'/images', $file->getClientOriginalName());
            return storage_path().'/images';
            return response()->json($file->getClientOriginalName());
        }
        
        if(!$request->hasFile('myfile')) {
        	return 'no';
        }
        
    }
    
    

    public function update(Request $request, Article $article)
    {
        $article->update($request->all());

        return response()->json($article, 200);
    }

    public function delete(Article $article)
    {
        $article->delete();

        return response()->json(null, 204);
    }
    
    
    
    public function userCategory($id) {
    	
    	$article = Article::where('articles.user_id', $id)
    		->join('categories', 'categories.id', '=', 'articles.category_id')
    		->select('articles.*','categories.name as category_name')
    		->orderBy('articles.updated_at', 'desc')
    		->get();
    		
    	return response()->json($article, 200);
    }
    
    public function userArticles ($user_id) 
    {
    	$articles =Article::where('user_id', $user_id)
    	->join('categories', 'categories.id', '=', 'articles.category_id')
    	->join('article_types', 'article_types.id','=', 'articles.type')
    	->select('articles.*'
    			,'categories.name as category_name'
    			,'article_types.name as artycleType'
)
    	->orderBy('articles.updated_at', 'desc')
    	->get();
    	
    	return ArticleResource::collection($articles);
    	// return response()->json($articles, 200);
    }
    
    public function comments () 
    {
    	
    	return response()->json(Comment::all(), 200);
    }
    
    public function userComments(Article $article, User $user )
    {
        //return $article;
        $comment = Comment::where('user_id', $user)->where('article_id', $article)->get();
        return response()->json($comment, 200);
    }
    





    public function detail(Article $article)
    {
    	
    	return new ArticleResource($article);
    	$img = Article::find($article);
    	
    	 $img->getFirstMediaUrl('images');
    	

    	$articles = Article::where('articles.id', $article)
            ->join('users', 'users.id', '=', 'articles.user_id')
            ->join('categories', 'categories.id', '=', 'articles.category_id')
            ->select('articles.*','users.name as author', 'categories.name as category_name')
            ->get();
            
            if ($articles->count() > 0) {
       	  return response()->json(ArticleResource::collection($articles)[0], Response::HTTP_OK);
       } else {
       	return response()->json([],Response::HTTP_OK);
       } 
        // $articles->getFirstMediaUrl();
            return response()->json($articles[0], 200);
    } 
    
    
    
    
    
    
    
    /*-------АДМИНСКАЯ ЧАСТЬ-----------------*/
    public function adminArticles()
    {
    
    	$article = DB::table('articles')
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
    
    
    
   	public function addType(Request $request)
    {
    	 $valiator = $request->validate([
            'name' => 'required',
            'author_id' => 'required',
        ]);
        
        $type = ArticleType::create($request->all());
        
        
        //если есть image
       if($request->hasFile('image') && $request->file('image')->isValid()){
            $type->addMediaFromRequest('image')->toMediaCollection('images');
        }
        
        
        
        
        
        $type_info = ArticleType::select('name', 'author_id')
        	->where('id', $type->id)
        	->get();
        	
        	
        	
    	$user = User::select('name')->where('id', $request->author_id )->get();

        $data = array(
    		'name'	=> $type_info[0]->name
	    	);

		
        return response()->json($data, Response::HTTP_OK);
    }
   
	public function getTypes()
    {
    	
    	$articleType = ArticleType::all();
    	
    	return response()->json($articleType, 200);
    }
    
}








































