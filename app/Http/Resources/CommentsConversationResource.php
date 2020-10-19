<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentsConversationResource extends JsonResource
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
        'replies' => $this->replies,
        'created_at'=>$this->created_at,
        'updated_at'=>$this->updated_at
      ];
    }
}
