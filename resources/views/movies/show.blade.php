@extends('layouts.app')
@section('title', ($video->title ?? $video->title_alt) . " ($video->year)")

@section('content')
    <div class="flex mt-5">
        <div class="w-2/5">
            <img src="{{ asset("storage/films/$video->id/poster.jpg") }}" alt="Постер">
        </div>
        <div class="w-3/5 ml-10">
            <div class="pb-2 border-b border-gray-500">
                <h3 class="text-2xl font-bold text-blue-700">{{ $video->title ?? $video->title_alt }}</h3>
                @if ($video->title_alt && $video->title)
                    <h3 class="text-lg font-bold text-gray-600 pb-1">{{ $video->title_alt }}</h3>
                @endif
            </div>
            <div class="flex items-center py-2 border-b border-gray-500">
                <div class="w-1/6 py-1 text-gray-600 font-bold">Страна:</div>
                <p class="flex-grow py-1">
                    @foreach ($video->countries as $country)
                        @if ($loop->last)
                            <span>{{ $country->name }}</span>
                        @else
                            <span>{{ $country->name }},</span>
                        @endif
                    @endforeach
                </p>
            </div>
            <div class="flex items-center py-2 border-b border-gray-500">
                <div class="w-1/6 py-1 text-gray-600 font-bold">Жанр:</div>
                <p class="flex-grow py-1">
                    @foreach ($video->genres as $genre)
                        @if ($loop->last)
                            <span>{{ $genre->name }}</span>
                        @else
                            <span>{{ $genre->name }},</span>
                        @endif
                    @endforeach
                </p>
            </div>
            <div class="flex items-center py-2 border-b border-gray-500">
                <div class="w-1/6 py-1 text-gray-600 font-bold">Режиссер:</div>
                <p class="flex-grow py-1">
                    @foreach ($people['directors'] as $director)
                        @if ($loop->last)
                            <span>{{ $director->name }}</span>
                        @else
                            <span>{{ $director->name }},</span>
                        @endif
                    @endforeach
                </p>
            </div>
            <div class="flex items-center py-2 border-b border-gray-500">
                <div class="w-1/6 py-1 text-gray-600 font-bold">Сценарий:</div>
                    @foreach ($people['screenwriters'] as $screenwriter)
                        @if ($loop->last)
                            <span>{{ $screenwriter->name }}</span>
                        @else
                            <span>{{ $screenwriter->name }},&nbsp;</span>
                        @endif
                    @endforeach
            </div>
            <div class="flex py-2">
                <p class="flex-grow py-1 text-black text-justify" style="text-indent: 1cm;">
                    {{ $video->description }}
                </p>
            </div>
        </div>
    </div>
    {{-- <video class="w-3/4 mx-auto" preload="metadata" controls="controls" src="videos/Miku Expo 2016 Live Concert In Toronto - Ten Thous.mp4">
        
    </video> --}}
@endsection
