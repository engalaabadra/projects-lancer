<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
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
    public function comments(){
        return $this->hasMany("App\Comment");
    }
}
