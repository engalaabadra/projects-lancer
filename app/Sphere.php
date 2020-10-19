<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sphere extends Model
{
    public function arena(){
        return $this->belongToMany("App\Arena");
    }
    public function replies(){
        return $this->hasMany("App\Reply");
    }
    public function user(){
        return $this->belongToMany("App\User");
    }
    public function userFoundedSphere(){
        return $this->belongsTo("App\User");
    }
    public function usersSphere(){
        return $this->belongsToMany('App\User', 'sphere_users');
    }
    public function posts(){
        return $this->hasMany("App\Post");
    }

    public function comments(){
        return $this->hasMany("App\Comment");
    }

    public function projects(){
        return $this->hasMany("App\Project");
    }
    public function tasks(){
        return $this->hasMany("App\Task");
    }
    public function surveys(){
        return $this->hasMany("App\Survey");
    }
    public function conversations(){
        return $this->hasMany("App\Conversation");
    }
    public function events(){
        return $this->hasMany("App\Events");
    }
}

