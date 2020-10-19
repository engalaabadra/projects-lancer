<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password','provider', 'provider_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function receivesBroadcastNotificationsOn()
    {
        return 'App.User.'.$this->id;
    }

    function socialProviders(){
        return $this->hasMany(SocailProvider::class);
    }
    public function mentions(){
        return $this->hasMany("App\Mention");
    }
    public function backupImages(){
        return $this->hasMany("App\BackupUserImage");
    }

    
    public function replies(){
        return $this->hasMany("App\Reply");
    }
    public function spheres(){
    return $this->belongsToMany('App\Sphere', 'sphere_users', 'user_id', 'sphere_id');

    }
    public function conversations(){
        return $this->belongsToMany('App\Conversation', 'conversations_users', 'user_id', 'conversation_id');

    }

    public function projectsJoined(){
        return $this->belongsToMany('App\Project', 'projects_users', 'user_id', 'project_id');

    }
    public function spheresJoined(){
        return $this->belongsToMany('App\Sphere', 'sphere_users', 'user_id', 'sphere_id');

    }
    
    public function spheresFounded(){
        return $this->hasMany("App\Sphere");
    }

    public function posts(){
        return $this->hasMany("App\Post");
    }

    public function comments(){
        return $this->hasMany("App\Comment");
    }

    // public function projects(){
    //     return $this->hasMany("App\Project");
    // }
    public function tasks(){
        return $this->hasMany("App\Task");
    }
    public function surveys(){
        return $this->hasMany("App\Survey");
    }

    public function events(){
       return $this->belongsToMany('App\Event', 'events_users');
    }
    

    public function projects(){
        return $this->belongsToMany('App\Event', 'projects_users');
     }
     public function projectsUser(){
        return $this->belongsToMany('App\Project', 'projects_users');
     }
     

    public function votes(){
        return $this->hasMany("App\Vote");
    }

    public function getNameAttribute($value){
        return ucfirst($value);//acceser
    }
    public function messages(){
        
        return $this->hasMany('App\Message');
    } 
}

