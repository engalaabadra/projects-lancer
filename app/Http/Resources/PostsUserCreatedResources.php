<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostsUserCreatedResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      return [
        'id'=>$this->id,
        'sphere_id'=>$this->sphere_id,
        'title'=>$this->title,
        'body'=>$this->body,
        'created_at'=>$this->created_at,
        'user' => $this->user,
        'sphere' => $this->sphere

      ];
    }
}
