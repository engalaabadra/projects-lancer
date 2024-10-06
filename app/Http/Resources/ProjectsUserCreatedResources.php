<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectsUserCreatedResources extends JsonResource
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
        'image'=>$this->image,
        'name'=>$this->name,
        'description'=>$this->description,
        'created_at'=>$this->created_at,
        'start_project'=>$this->start_project,
        'end_project'=>$this->end_project,
        'created_at'=>$this->created_at,
        'user' => $this->user,
        'sphere' => $this->sphere

    ];
    }
}
