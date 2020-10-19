<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TasksUserAcceptedResources extends JsonResource
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
        'status_task_invitation'=>$this->status_task_invitation,
        'status_task'=>$this->status_task,
        'name_task'=>$this->name_task,
        'description_task'=>$this->description_task,
        'start_task'=>$this->start_task,
        'end_task'=>$this->end_task,
        'created_at'=>$this->created_at,
        'user' => $this->user,
        'sphere' => $this->sphere,
        'project'=>$this->project


    ];
    }
}
