<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    //
    public function job(){
        return $this->belongsTo(Jobe::class);
    }
    public function categories(){
        return $this->belongsTo(Category::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function employer(){
        return $this->belongsTo(User::class,'employer_id');
    }

}
