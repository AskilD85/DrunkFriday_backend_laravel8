<?php

namespace App\Resources;


final class FileResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
        ];
    }
}
