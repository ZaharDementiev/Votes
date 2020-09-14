@extends('layouts/app')

@section('content')
    @if($posts->isEmpty())
        <h3>Нет постов для подтверждения...</h3>
    @else
    <h1 style="margin-bottom: 30px; font-size: 30px">Требуют подтверждения</h1>
    <div class="unapproved">
            <ul>
                @foreach($posts as $post)
                    <form action="{{route('approve', $post->id)}}" id="form-{{$post->id}}" method="POST">
                        @csrf
                    </form>
                    <form action="{{ route('destroy', $post->id) }}" method="POST" id="delete-{{$post->id}}">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                    </form>

                    <li>
                                <a href="{{route('show', $post->slug)}}">{{$post->title}}</a>

                    </li>
                <div style="padding: 15px; display: flex">
                <div class="btn-green">
                    <a href="#" onclick="$('#form-{{$post->id}}').submit()">Одобрить</a>
                </div>
                <div class="btn-green">
                    <a href="#" onclick="$('#delete-{{$post->id}}').submit()">Удалить</a>
                </div>
                </div>
                @endforeach
            </ul>
        </div>
    @endif
@endsection
