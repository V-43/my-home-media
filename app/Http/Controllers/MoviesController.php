<?php

namespace App\Http\Controllers;

use App\KinopoiskApi;
use App\Models\Video;
use Illuminate\Http\Request;

class MoviesController extends Controller
{
    public function index()
    {
        $videos = Video::select('id', 'title', 'title_alt', 'created_at')->orderByDesc('id')->take(12)->get();
        return view('movies.index', ['videos' => $videos]);
    }

    public function show(Video $video)
    {
        foreach($video->people as $person) {
            if ($person->pivot->role & 1) {
                $people['directors'][] = $person;
            }
            if ($person->pivot->role & 2) {
                $people['screenwriters'][] = $person;
            }
        }
        
        return view('movies.show', compact('video', 'people'));
    }

    public function create($kinopoiskId)
    {
        $filmData = KinopoiskApi::find($kinopoiskId);
        return view('movies.create', ['filmData' => $filmData]);
    }
}
