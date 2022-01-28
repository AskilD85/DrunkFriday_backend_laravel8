<?php

namespace App\Resources;

use App\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


final class UserResource extends Resource
{
    public function toArray($request)
    {
        $user = $this->resource;
        
        // 'name', 'email', 'password', 'type', 'ava','desc','phone', 'verify_token', 'status'
        return [
            'id'                => $user->id,
            'first_name'        => $user->name,
            'email'         	=> $user->email,
            'type'      		=> $user->type,
            'desc'      		=> $user->desc,
            'phone'         	=> $user->phone,
            'email_status'      => $user->status === '10' ? 'Email подтверждён!' : 'Email не подверждён!',
            'created_at'		=> $user->created_at,
            'ava_url'				=> $user->ava ? Media::Find($user->ava)->getUrl('thumb_200x200') : null,
            // 'ava_url'			=> getFirstMediaUrl('avatars')->last()->getUrl('thumb_200x200')
          /*  'birth_date'         => optional($user->personal)->birth_date,
            'work_from'          => optional($user->specialization)->work_from,
            'work_to'            => optional($user->specialization)->work_to,
            'residence_address'  => optional($user->personal)->residence_address,
            'note'               => optional($user->personal)->note,
            'photo'              =>
                [
                    'photo_id'  => optional($user->personal)->photo_id,
                    'url'       => route('get-file',['id' => optional($user->personal)->photo_id])
                ]*/
        ];

    }
}
