@extends('layouts.app')
@section('title', 'Главная')

@section('content')
    <div class="flex mt-5">
        <div>
            <img src="/images/300x450.webp" alt="Постер">
            <!-- <button class="bg-gray-500 border border-gray-700 py-2 px-4 rounded-lg w-full">Загрузить постер</button> -->
            <input style="max-width: 300px;" type="file" name="" id="" title="Загрузить постер">
        </div>
        <div class="w-3/5 ml-10">
            <div class="flex py-2 border-b border-gray-500">
                <div class="w-1/5 py-1">Видеофайл:</div>
                <input class="w-4/5 flex-grow" type="file" title="Загрузить видеофайл">
            </div>
            <div class="flex py-2 border-b border-gray-500">
                <div class="w-1/5 py-1">Название:</div>
                <input class="flex-grow px-3 py-1" type="text" value="{{ $movie['title'] }}">
            </div>
            @if (isset($movie['title_alternative']))
                <div class="flex py-2 border-b border-gray-500">
                    <div class="w-1/5 py-1">Name:</div>
                    <input class="flex-grow px-3 py-1" type="text" value="{{ $movie['title_alternative'] }}">
                </div>
            @endif
            <div class="flex py-2 border-b border-gray-500">
                <div class="w-1/5 py-1">Год:</div>
                <input class="flex-grow px-3 py-1" type="number" value="{{ $movie['year'] }}">
            </div>
            <div class="flex py-2 border-b border-gray-500">
                <div class="w-1/5 py-1">Страна:</div>
                <input class="flex-grow px-3 py-1" type="text" value="{{ implode(',', $movie['countries']) }}">
            </div>
            <div class="flex py-2 border-b border-gray-500">
                <div class="w-1/5 py-1">Жанр:</div>
                <input class="flex-grow px-3 py-1" type="text" value="{{ implode(',', $movie['genres']) }}">
            </div>
            <div class="flex py-2 border-b border-gray-500">
                <div class="w-1/5 py-1">Режиссер:</div>
                <input class="flex-grow px-3 py-1" type="text" value="{{ implode(',', $movie['directors']) }}">
            </div>
            <div class="flex py-2 border-b border-gray-500">
                <div class="w-1/5 py-1">Сценарий:</div>
                <input class="flex-grow px-3 py-1" type="text" value="{{ implode(',', $movie['screenwriters']) }}">
            </div>
            <div class="flex py-2 border-b border-gray-500">
                <div class="w-1/5 py-1">Время:</div>
                <input class="flex-grow px-3 py-1" type="text" value="{{ $movie['collapse']['duration'][0] }}">
            </div>
            <div class="flex py-2 border-b border-gray-500">
                <div class="w-1/5 py-1">Возраст:</div>
                <input class="flex-grow px-3 py-1" type="text" value="{{ $movie['age'] }}">
            </div>
            <div class="flex py-2">
                <div class="w-1/5 py-1">Описание:</div>
                <textarea class="flex-grow px-3 py-1" rows="5">{{ $movie['description'] }}</textarea>
            </div>
        </div>
    </div>
@endsection
