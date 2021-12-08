<?php

namespace App\Http\Controllers;
use App\Article;
use App\Blog;
use App\Category;
use App\Comment;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Mail;
use Illuminate\Support\Facades\Auth;


class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Blog::where('active',1)->orderBy('created_at', 'DESC')->get();
        return response()->json($articles, 200);
    }
    
    
    
/*показать  для определённого пользователя статьи*/
	public function show_for_user($user_id)
	{
		
       
        
        $articles = DB::table('blogs')->where('blogs.user_id', $user_id)
    		// ->join('blogs', 'blogs.id', '=', 'blogs.category_id')
    		->orderBy('blogs.updated_at', 'desc')
    		->get();
        
        
        
        return response()->json($articles, 200);
	}
	
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $article = Blog::create($request->all());
        return response()->json($article, 200);
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
    public function show($id)
    {
        //
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
    public function update(Request $request, Blog $article)
    {
        $article->update($request->all());

        return response()->json($article, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
