<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;
use Illuminate\Notifications\Notifiable;

class Article extends Model implements HasMedia
{
	use HasFactory, InteractsWithMedia;
    use Notifiable;
    
     public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(100)
              ->height(100)
              ->sharpen(10);
        
        $this->addMediaConversion('thumb_200x200')
              ->width(200)
              ->height(200)
              ->sharpen(10);
              
        $this->addMediaConversion('old-picture')
              ->sepia()
              ->border(10, 'black', Manipulations::BORDER_OVERLAY);
        $this->addMediaConversion('thumb-cropped')
            ->crop('crop-center', 400, 400);
    }
    
    protected $fillable = ['title', 'body','image', 'user_id', 'category_id', 'active', 'type','city_id',];
}