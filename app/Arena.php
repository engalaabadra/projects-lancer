<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Arena extends Model
{
    public function spheres(){
        return $this->hasMany("App\Sphere");
    }
}
