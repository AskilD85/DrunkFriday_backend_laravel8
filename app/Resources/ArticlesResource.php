<?php

namespace App\Resources;

use Spatie\MediaLibrary\MediaCollections\Models\Media;


final class ArticlesResource extends Resource
{
    public function toArray($request)
    {
        $articles = $this->resource;
        
            // protected $fillable = ['title', 'body','image', 'user_id', 'category_id', 'active', 'type','city_id',];

        return [
        
    	'users'         => ArticleResource::collection(
                $articles
            ),
        ];

    }
}
