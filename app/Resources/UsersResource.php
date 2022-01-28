<?php

namespace App\Resources;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class UsersResource extends Resource
{
    public function toArray($request)
    {
        /** @var LengthAwarePaginator $paginator */
        // $paginator = $this->resource;
        
        // return dd($paginator);
		$users = $this->resource;
        return [
            'pagination' => [],
             /*   // 'total'         => $paginator->total(),
                'per_page'      => $paginator->perPage(),
                'current_page'  => $paginator->currentPage(),
                'last_page'     => $paginator->lastPage(),
                ],*/

            'users'         => UserResource::collection(
                $users
            ),

        ];
    }
}