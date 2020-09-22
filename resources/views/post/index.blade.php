@extends('layouts/app')
@section('content')
    @if($filter == 'live')
        <div class="tape-tabs tape-tabs_small">
            <ul>
                <li class="active"><a href="/">Live</a></li>
                <li><a href="{{route('popular')}}">Лучшее</a></li>
                <li><a href="{{route('discussed')}}">Обсуждаемое</a></li>
            </ul>
        </div>
    @elseif($filter == 'popular')
        <div class="tape-tabs tape-tabs_small">
            <ul>
                <li><a href="/">Live</a></li>
                <li class="active"><a href="{{route('popular')}}">Лучшее</a></li>
                <li><a href="{{route('discussed')}}">Обсуждаемое</a></li>
            </ul>
        </div>
        <div class="tape-tabs">
            <ul>
                <li class="{{ $time == 'day' ? 'active' : '' }}"><a href="{{ route('popular', [ 'day']) }}">24 часа</a></li>
                <li class="{{ $time == 'week' ? 'active' : '' }}"><a href="{{ route('popular', [ 'week']) }}">7 дней</a></li>
                <li class="{{ $time == 'month' ? 'active' : '' }}"><a href="{{ route('popular', ['month']) }}">30 дней</a></li>
                <li class="{{ $time == 'century' ? 'active' : '' }}"><a href='{{ route('popular', ['century']) }}'>Все время</a></li>
            </ul>
        </div>
    @elseif($filter == 'discussed')
        <div class="tape-tabs tape-tabs_small">
            <ul>
                <li><a href="/">Live</a></li>
                <li><a href="{{route('popular')}}">Лучшее</a></li>
                <li class="active"><a href="{{route('discussed')}}">Обсуждаемое</a></li>
            </ul>
        </div>
        <div class="tape-tabs">
            <ul>
                <li class="{{ $time == 'day' ? 'active' : '' }}"><a href="{{ route('discussed', [ 'day']) }}">24 часа</a></li>
                <li class="{{ $time == 'week' ? 'active' : '' }}"><a href="{{ route('discussed', [ 'week']) }}">7 дней</a></li>
                <li class="{{ $time == 'month' ? 'active' : '' }}"><a href="{{ route('discussed', ['month']) }}">30 дней</a></li>
                <li class="{{ $time == 'century' ? 'active' : '' }}"><a href='{{ route('discussed', ['century']) }}'>Все время</a></li>
            </ul>
        </div>

    @endif

    <posts :posts="{{$posts}}"></posts>


@endsection
