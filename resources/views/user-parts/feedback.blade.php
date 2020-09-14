@extends('profile')

@section('inner')

    <div class="wrap-profile-content">
        <h2>Отзывы</h2>
        <div class="wrap-followers">
            <ul>
            @foreach($user->feedbacks as $feedback)
                <li>
                    {{$feedback->text}}
                    {{$feedback->points}}
                    {{$feedback->positive}}
                </li>
            @endforeach
            </ul>
        </div>
    </div>

@endsection

