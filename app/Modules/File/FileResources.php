<?php

namespace App\Modules\File;

use App\Resources\Resource;

final class FileResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
        ];
    }
}
