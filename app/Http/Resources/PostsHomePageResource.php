<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostsHomePageResource extends JsonResource
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
            'title'=>$this->title,
            'body'=>$this->body,
            'comments' => $this->comments,
            'user_id'=>$this->user_id,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at
        ];
    }
}
