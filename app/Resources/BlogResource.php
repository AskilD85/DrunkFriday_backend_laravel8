<?php

namespace App\Resources;


use Illuminate\Http\Response;

final class BlogResource extends Resource
{
    public function toArray($request)
    {
        $post = $this->resource;

        // protected $fillable = ['title', 'body','image', 'user_id', 'category_id', 'active', 'type','city_id',];

        return [

            'id'		=> $post->id
            ,'title'		=> $post->title
            ,'body'		=> $post->body
//            ,'type'		=> $post->type
//            ,'typeName'	=> $post->artycleType
            ,'username'	=> $post->username
//            ,'category_name'=> $post->category_name
//            ,'category_id'=> $post->category_id
//            ,'city_id'	=> $post->city_id
            ,'created_at'=>	$post->created_at
            ,'updated_at'=>	$post->updated_at
            ,'created_at'=>	$post->created_at
            ,'active'=>	$post->active
//           , 'img_url'	=>  $post->getFirstMediaUrl('images')
            , Response::HTTP_OK];
    }
}
