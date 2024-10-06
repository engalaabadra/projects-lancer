<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectDetailsResource extends JsonResource
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
            'user_id'=>$this->user_id,
            'name'=>$this->name,
            'description'=>$this->description,
            'created_at'=>$this->created_at
        ];
    }
}
