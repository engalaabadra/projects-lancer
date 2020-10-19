<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    
    public function users(){
        return $this->belongsToMany('App\User', 'events_users');
    }
    public function user(){
        return $this->belongsTo("App\User");
    }
    public function sphere(){
        return $this->belongsTo("App\Sphere");
    }
}
