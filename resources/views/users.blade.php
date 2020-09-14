@extends('layouts.app')
@section('content')

    <table>
        <thead>
        <tr>
            <th>Логин</th>
            <th>Email</th>
            <th>Время регистрации</th>
            <th>Время посещения</th>
            <th>Подтверждён</th>
            <th>Посты</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
        <tr>
            <td style="text-decoration: underline"><a href="{{route('profile', $user->name)}}">{{$user->name}}</a></td>
            <td>{{$user->email}}</td>
            <td>{{$user->created_at}}</td>
            <td>
                @if($user->last_online_at->diffInMinutes(now()) < 5 )
                    Online
                @else
                    {{ $user->last_online_at->diffForHumans() }}
                @endif
            </td>
            <td>{{$user->email_verified_at == null ? 'нет' : 'да'}}</td>
            <td>{{$user->posts()->count()}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>

@endsection
