<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MoviesController extends Controller
{
    public function index()
    {
        return view('movies.index');
    }

    public function show($id)
    {
        
    }

    public function create($kinopoiskId)
    {
        $movie = HTTP::get("https://api.kinopoisk.cloud/movies/$kinopoiskId/token/9301b8115343353d80d3b034576daece")
            ->json(); //"кто я" не выводит время (['collapse']['duration'] == null)
        \dump($movie);
        return view('movies.create', ['movie' => $movie]);
    }
}
