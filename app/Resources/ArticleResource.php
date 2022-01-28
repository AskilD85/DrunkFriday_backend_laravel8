<?php

namespace App\Resources;

use App\Article;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Http\Response;


final class ArticleResource extends Resource
{
    public function toArray($request)
    {
        $article = $this->resource;
        
            // protected $fillable = ['title', 'body','image', 'user_id', 'category_id', 'active', 'type','city_id',];

        return [
        
    		'id'		=> $article->id,
	    	'title'		=> $article->title,
	    	'body'		=> $article->body,
	    	'type'		=> $article->type,
	    	'typeName'	=> $article->artycleType,
	    	'username'	=> $article->username,
	    	'category_name'=> $article->category_name,
	    	'category_id'=> $article->category_id,
	    	'city_id'	=> $article->city_id,
	    	'created_at'=>	$article->created_at,
	    	'updated_at'=>	$article->updated_at,
	    	'active'=>	$article->active,
	    	'img_url'	=>  $article->getFirstMediaUrl('images')
        , Response::HTTP_OK];

    }
}
