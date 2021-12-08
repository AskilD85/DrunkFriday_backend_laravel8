<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Article;
use App\User;
use Mail;
// use \App\Mail\DemoEmail;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    	
        $response = Comment::create($request->all());
        
        $article_id = $request->json('article_id');
        
        
        // получаем id автора
        //$user_id = Article::select('user_id')->where('articles.id', $article_id)->get();
    	
    	$user_id = Article::findOrFail($request->json('article_id'));
    	
    	//автор поста
       	$user = User::select('email', 'id', 'name')->where('id', $user_id->user_id)->get();
       	
       	$comment_author = User::select('email', 'id', 'name')->where('id', $response->user_id)->get(); 

	    $data = array(
	    	'name'		=>'Добавлен комментарий!',
	    	'link_id'	=> $article_id,
	    	'text'		=> $request->input('text'),
	    	'name'		=> $user[0]->name,
	    	'comment_author' => $comment_author[0]-> name,
	    	'comment_author_id' => $response->user_id,
	    	'id'		=> $user[0]->id
	    	);

		//return $data;
		if ($response) {
			
			Mail::send(['html'=>'mail/addComment'], $data, function($message) use ($user) {
        	$message->
        			to($user[0]->email)
        			 //->to('askildar@yandex.ru')
	    			->subject('Оставлен комментарий!');
	        $message->from('info@master702.ru');
	    	});	


	    	
	    	Mail::send(['html'=>'mail/addComment'], $data, function($message) use ($user) {
        	$message
        			// to($user[0]->email)
        			 ->to('askildar@yandex.ru')
	    			->subject('Оставлен комментарий!');
	        $message->from('info@master702.ru');
	    	});	
		}
	   
     
        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $article, $user)
    {
        $articles = Article::where('user_id', $user)
                ->where('id', $article)
                ->count();
     
    	$isAdmin = User::where('id', $user)
    		->where('type', 'admin')
    		->get()
    		->count();
    		
    		
        // если статья автора - показываем все комменты
        if ($articles == 1 && $isAdmin !== 1) {

            $comments = DB::table('comments')->where('comments.article_id', $article)
            	->join('users','users.id', '=', 'comments.user_id')
            	->select('comments.*', 'users.name as username')
            	->orderBy('updated_at', 'desc')
            	->get();
            	
            return response()->json($comments, 200);
            
        // если статья не автора - показывваем только его комменты
        } elseif ($articles !== 1 && $isAdmin !== 1) {
            
            $comments = DB::table('comments')->where('comments.user_id', $user )
            	->where('comments.article_id', $article)
            	->join('users','users.id', '=', 'comments.user_id')
            	->select('comments.*', 'users.name as username')
            	->orderBy('updated_at', 'desc')
            	->get();
            return response()->json($comments, 200);
         
        // если админ , и автор  - показываем его комменты
        } elseif ($isAdmin == 1 || $articles == 1) {
        	
        	$comments = DB::table('comments')->where('comments.article_id', $article)
        	->join('users','users.id', '=', 'comments.user_id')
        	->select('comments.*', 'users.name as username')
        	->orderBy('updated_at', 'desc')
        	->get();
        	return response()->json($comments, 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json(null, 204);
    }
}
