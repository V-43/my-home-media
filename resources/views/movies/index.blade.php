@extends('layouts.app')
@section('title', 'Главная')

@section('content')
    <h3 class="text-2xl font-bold text-center text-blue-700 pb-3">Последние загрузки</h3>
    <ul class="flex flex-wrap justify-between">
        @foreach($videos as $video)
            <li class="pb-5 transition transform ease-in-out duration-300 hover:-translate-y-1 hover:scale-105 motion-reduce:hover:transform-none">
                <a href="{{ route('movies.show', ['id' => $video->id]) }}">
                    <img src="{{ asset("storage/films/$video->id/poster.jpg") }}" alt="Постер" title="" width="200px">
                    <h4 style="width: 200px;" class="text-center">{{ $video->title ?? $video->title_alt }}</h4>
                </a>
            </li>
        @endforeach
    </ul>
@endsection
