<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TasksResource extends JsonResource
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
            'name'=>$this->name,
            'category_id'=>$this->category_id,
            'user_id'=>$this->user_id,
            'member_id'=>$this->member_id,
            'sphere_id'=>$this->sphere_id,
            'project_id'=>$this->project_id,

    
    
        ];
    }
}
