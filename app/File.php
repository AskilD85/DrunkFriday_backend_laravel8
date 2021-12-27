<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Загружаемые файлы
 * 
 * @property string $name Наименование файла
 * @property int $user_id
 * @property User $user Пользователь который загрузил файл
 */
final class File extends Model implements HasMedia
{
	use HasFactory,InteractsWithMedia;
	
	protected $fillable= ['title','body']
    public function user()
    {
        $this->hasOne(User::class, 'id', 'user_id');
    }
}
