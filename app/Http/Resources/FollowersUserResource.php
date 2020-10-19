<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FollowersUserResource extends JsonResource
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
            'follower_id'=>$this->follower_id,
            'username'=>$this->username,
            'email'=>$this->email,
            'job_title'=>$this->job_title,
        ];
    }
}
