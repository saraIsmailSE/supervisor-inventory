<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'supervisor_timer ',
        'advisor_timer ',
    ];

    public function LeaderDuty()
    {
        return $this->hasMany('App\Models\LeaderDuty','week_id');
    }

    public function ExtraWork()
    {
        return $this->hasMany('App\Models\ExtraWork','week_id');
    }

    public function RepeatedNote()
    {
        return $this->hasMany('App\Models\RepeatedNote','week_id');
    }

    public function News()
    {
        return $this->hasMany('App\Models\News','week_id');
    }

    public function repeatnote()
    {
        return $this->hasMany('App\Models\RepeatedNote');
    }
    
}
