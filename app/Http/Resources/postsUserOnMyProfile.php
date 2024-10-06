<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class postsUserOnMyProfile extends JsonResource
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
            'user_id'=>$this->user_id,
            'member_id'=>$this->member_id,
            'title'=>$this->title,
            'body'=>$this->body,
            'username'=>$this->username,
            'email'=>$this->email,
            'job_title'=>$this->job_title,
            'image'=>$this->image,

        ];
    }
}
