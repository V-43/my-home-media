<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function path()
    {
        return route('movies.show', $this->id);
    }

    public function dirPath()
    {
        return "storage/films/$this->id/";
    }

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

    /* 
    следующие 3 метода написаны для "галочки", они нигде не используются
    это обусловлено тем, что на страницах, пока нет страниц, где требовались бы конкретные люди
    */
    public function directors()
    {
        return $this->belongsToMany('App\Models\Person')->wherePivot('role', '&', 1);
    }

    public function screenwriters()
    {
        return $this->belongsToMany('App\Models\Person')->wherePivot('role', '&', 2);
    }

    public function actors() //актеры пока не реализованы
    {
        return $this->belongsToMany('App\Models\Person')->wherePivot('role', '&', 4);
    }
}
