<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    public function countries()
    {
        return $this->belongsToMany('App\Models\Country');
    }

    public function genres()
    {
        return $this->belongsToMany('App\Models\Genre');
    }

    public function people()
    {
        return $this->belongsToMany('App\Models\Person')->withPivot('role');
    }
}
