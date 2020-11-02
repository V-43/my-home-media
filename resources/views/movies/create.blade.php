@extends('layouts.app')
@section('title', 'Добавление нового фильма')

@section('content')
    <livewire:create-film :filmData="$filmData">
@endsection
