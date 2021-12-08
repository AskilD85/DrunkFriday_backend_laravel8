<?php
namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Comment;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mail;

use App\Models\File\File;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Auth;

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
       ->select('articles.*', 'categories.name as category_name')
       ->orderBy('updated_at', 'desc')
       ->get();
       if ($article->count() !== 0) {
       		return response()->json($article, 200);
       } else {
       	return response()->json([]);
       }

    }

    public function show($article)
    {

        return response()->json($article, 200);
    }

    public function store(Request $request)
    {
    	 $valiator = $request->validate([
            'title' => 'required',
            'body' => 'required',
            'image' => 'required',
        ]);
        
        $article = Article::create($request->all());
<<<<<<< HEAD
        
        
        //если есть image
       if($request->hasFile('image') && $request->file('image')->isValid()){
            $article->addMediaFromRequest('image')->toMediaCollection('images');
        }
        
        
        
        
        
=======

>>>>>>> bc29111335e2638ff4ed1c502da693fcceca1f1f
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

		
/*
        if($request->hasFile('myfile')) {
            $file = $request->file('myfile');
            $file->move(storage_path().'/images', $article_info[0]->user_id.'_myfile.img');
            return response()->json('{"ok":"ok"}');
<<<<<<< HEAD
        }*/
        
=======
        }


>>>>>>> bc29111335e2638ff4ed1c502da693fcceca1f1f

       /* if ($article) {
			Mail::send(['html'=>'mail/addArticle'], $data, function($message) {
        	$message->to('askildar@yandex.ru')
	    			->subject('Добавлен новый пост!');
	        $message->from('info@master702.ru');
	    	});
		}*/
<<<<<<< HEAD
		
        return response()->json($data, Response::HTTP_OK);
    }
   
   
   
   
=======

        return response()->json($foo, Response::HTTP_OK);
    }


>>>>>>> bc29111335e2638ff4ed1c502da693fcceca1f1f
     public function add(Request $request)
    {
        $article = Article::create($request->all());
        return response()->json($article, 200);
    }
<<<<<<< HEAD
    
    
    
    
  
  
    
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
 
 
 
 
    
    
    
=======




>>>>>>> bc29111335e2638ff4ed1c502da693fcceca1f1f
    public function myfile(Request $request)
    {


<<<<<<< HEAD

          if($request->hasFile('myfile')) {
            $file = $request->file('myfile');
           
           Storage::put('avatars/1', $file);


           return Storage::put('avatars/'.$request->user_id, $file);
           
           
            $file->move(storage_path().'/images', $file->getClientOriginalName());
            return storage_path().'/images';
            return response()->json($file->getClientOriginalName());
=======
          if($request->hasFile('myfile')) {
            $file = $request->file('myfile');

            $file->move(storage_path().'/images', '222'.$file->getClientOriginalName());
            return response()->json('{"yes":"получилось"}');
>>>>>>> bc29111335e2638ff4ed1c502da693fcceca1f1f
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
<<<<<<< HEAD
    	
    	$article = Article::where('articles.user_id', $id)
=======

    	$article = DB::table('articles')->where('articles.user_id', $id)
>>>>>>> bc29111335e2638ff4ed1c502da693fcceca1f1f
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
    	->select('articles.*', 'categories.name as category_name')
    	->get();
    	return response()->json($articles, 200);
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
     public function addCategory(Request $request)
    {
        $category = Category::create($request->all());
        return response()->json($category, 200);
    }

    public function detail($article)
    {
<<<<<<< HEAD
    
    	$articles = Article::where('articles.id', $article)
=======

    	$articles = DB::table('articles')->where('articles.id', $article)
>>>>>>> bc29111335e2638ff4ed1c502da693fcceca1f1f
            ->join('users', 'users.id', '=', 'articles.user_id')
            ->join('categories', 'categories.id', '=', 'articles.category_id')
            ->select('articles.*','users.name as author', 'categories.name as category_name')
            ->get();
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

}








































