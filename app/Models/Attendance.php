<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable=["user_id","start_time","date"];
    protected $dates=["start_time"];
    public function user(){ //追記
        // return $this->belongsTo('App\Models\Attendance');
        return $this->belongsTo(User::class);
    }
    public function rests(){
        // return $this->hasMany('App\Models\Rest');
       return $this->hasMany(Rest::class);
    }
}