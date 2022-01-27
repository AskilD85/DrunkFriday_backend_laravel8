<?php
namespace App\Http\Controllers;

use App\Car;
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

use Illuminate\Support\Facades\Storage;


class CarController extends Controller
{
    public function index()
    {
    	$car = Car::all();


       if ($car->count() > 0) {
       		return response()->json($car, 200);
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
        ]);

        $article = Article::create($request->all());








        $article_info = Article::select('title', 'body', 'user_id', 'category_id', 'active', 'type','id','city_id')
        	->where('id', $article->id)
        	->get();



    	$user = User::select('name')->where('id',$article_info[0]->user_id )->get();
        $categ = Category::select('name')->where('id',$article_info[0]->category_id)->get();

        //если есть image
        if($request->hasFile('image') && $request->file('image')->isValid()){
           $article->addMediaFromRequest('image')->toMediaCollection('images');
        }
        $data = array(
    		'link_id'	=> $article_info[0]->id,
	    	'title'		=> $article_info[0]->title,
	    	'text'		=> $article_info[0]->body,
	    	'type'		=> $article_info[0]->type,
	    	'username'	=> $user[0]->name,
	    	'categ_name'=> $categ ? $categ[0]->name: null,
	    	'user_id'	=> $article_info ? $article_info[0]->user_id: null,
	    	'city_id'	=> $article_info[0]->city_id,
	    	'img_url' => $article->getFirstMediaUrl('images')
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
        $car = Car::create($request->all());
        return response()->json($car, 200);
    }




    public function update(Request $request, Car $car)
    {
        $car->update($request->all());

        return response()->json($car, 200);
    }

    public function destroy(Car $car)
    {
        $car->delete();

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


    public function detail($article)
    {

    	$articles = Article::where('articles.id', $article)
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








































