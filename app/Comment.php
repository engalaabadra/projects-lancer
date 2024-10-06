<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function mentions(){
        return $this->hasMany("App\Mention");
    }
    public function replies(){
        return $this->hasMany("App\Reply");
    }
    public function user(){
        return $this->belongsTo("App\User");
    }
    public function sphere(){
        return $this->belongsTo("App\Sphere");
    }
    public function post(){//fun to this comment for this post
        return $this->belongsTo("App\Post");
    }
    public function project(){
        return $this->belongsTo("App\Project");
    }
    public function conversation(){
        return $this->belongsTo("App\Conversation");
    }
    public function task(){
        return $this->belongsTo("App\Task");
    }

}
