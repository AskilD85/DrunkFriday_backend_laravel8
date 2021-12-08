<?php
namespace App\Http\Controllers;

use App\Article;
use App\ArticleType;
use App\Category;
use App\Comment;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mail;

use App\Models\File\File;
use Illuminate\Contracts\Filesystem\Filesystem;

use Illuminate\Support\Facades\Storage;


class DataloadController extends Controller
{
    public function index()
    {
    	
    	$articleType = ArticleType::where('is_active','Y')->get();
    	
    	return response()->json($articleType, 200);
    }
    
    

    public function show($article)
    {
       
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
    	
    	$article = DB::table('articles')->where('articles.user_id', $id)
    		->join('categories', 'categories.id', '=', 'articles.category_id')
    		->select('articles.*','categories.name as category_name')
    		->orderBy('articles.updated_at', 'desc')
    		->get();
    		
    	return response()->json($article, 200);
    }
    
    public function userArticles ($user_id) 
    {
    	$articles = DB::table('articles')->where('user_id', $user_id)
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
    
    	$articles = DB::table('articles')->where('articles.id', $article)
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








































