<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobe extends Model
{
    use HasFactory;
    //
    public function categories(){
        return $this->belongsTo(Category::class,'category_id');
    }
    public function job_types(){
        return $this->belongsTo(JobType::class,'job_type_id');
    }
    public function applications(){
        return $this->hasMany(JobApplication::class,'job_id');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
