<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function replies(){
        return $this->hasMany("App\Reply");
    }
    public function users(){
        return $this->belongsToMany('App\User', 'events_users');
    }
    public function usersProject(){
        return $this->belongsToMany('App\User', 'projects_users');
    }
    public function user(){
        return $this->belongsTo("App\User");
    }
    public function sphere(){
        return $this->belongsTo("App\Sphere");
    }
    public function comments(){
        return $this->hasMany("App\Comment");
    }
    public function tasks(){
        return $this->hasMany("App\Task");
    }
    
    public function votes(){
        return $this->hasMany("App\Vote");
    }
}
