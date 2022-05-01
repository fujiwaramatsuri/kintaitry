<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rest extends Model
{
    use HasFactory;
    // 一体多リレーション
    protected $fillable = [
        // 'user_id',
        'date',
        'attendance_id',
        'rests_start',
        'rests_end'];
    public function attendance(){ //追記
        // return $this->belongsTo('App\Models\Attendance');
        return $this->belongsTo(Attendance::class);
}
}