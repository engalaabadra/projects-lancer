<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    public function replies(){
        return $this->hasMany("App\Reply");
    }
    public function users(){
        return $this->belongToMany("App\User");
    }
    public function sphere(){
        return $this->belongsTo("App\Sphere");
    }
    public function comments(){
        return $this->hasMany("App\Comment");
    }
}
