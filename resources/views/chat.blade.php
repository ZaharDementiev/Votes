@extends('layouts.app')

@section('content')

    <chat :user="{{ auth()->user() }}" :active-friend="{{ $user->id }} " :friend="{{$user}}"></chat>

@endsection
