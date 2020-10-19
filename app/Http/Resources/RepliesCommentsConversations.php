<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RepliesCommentsConversations extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      //  return parent::toArray($request);
      return [
        'id'=>$this->id,
        'body'=>$this->body,
        'conversation_id'=>$this->conversation_id,
        'sphere_id'=>$this->sphere_id,
        'user_id'=>$this->user_id,
        'comment_id'=>$this->comment_id
      ];
    }
}
