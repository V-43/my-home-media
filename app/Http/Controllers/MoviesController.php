<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MoviesController extends Controller
{
    public function index()
    {
        $videos = Video::select('id', 'title', 'title_alt')->orderByDesc('id')->take(16)->get();
        return view('movies.index', ['videos' => $videos]);
    }

    public function show($id)
    {
        $video = Video::with(['countries', 'genres', 'people'])->findOrFail($id);

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
        $filmData = Http::get("https://api.kinopoisk.cloud/movies/$kinopoiskId/token/9301b8115343353d80d3b034576daece")
            ->json(); //"кто я" не выводит время (['collapse']['duration'] == null)
        // \dump($filmData);
        $filmData['genres'] = array_map('mb_strtolower', $filmData['genres']);
        return view('movies.create', ['filmData' => $filmData]);
    }
}
