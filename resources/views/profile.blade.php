@extends('layouts.app')
@section('content')
    @php $owner = $user->id == Auth::id() @endphp
    <section class="sec-profile">
        <div class="wrap-profile">

                <div class="profile_user">
                    <div class="profile_user_img" style="background-image: url(/storage/images/avatars/{{ $user->avatar}});"></div>
                    <div class="profile_user_name">
                        <p>{{$user->name}}</p>
                    </div>
                    @if($owner)
                        <div class="profile-user-upload">
                            <p>Ваша фотография.<br>
                                Размер загружаемого файла<br>
                                не должен превышать 5МБ.
                            </p>
                            <div class="profile-user-upload__actions">
                                <div class="profile-user-upload__upload">
                                    <form id="avatar-form" action="/avatar" method="POST" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <label>
                                            <input type="file" name="avatar" onchange="$('#avatar-form').submit()">
                                            <span class="btn-green_nobtn white">Загрузить</span>
                                        </label>
                                    </form>
                                </div>
                                <div class="profile-user-upload__delete">
                                    <form action="/avatar-delete" method="POST" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn-green_nobtn">Удалить</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                @if(!$owner)
                        <div class="profile_user_time">
                            @if($user->last_online_at->diffInMinutes(now()) < 5 )
                                <p>Online</p>
                            @else
                            <p>Была в сети {{ $user->last_online_at->diffForHumans() }}</p>
                            @endif
                        </div>
                    <div onclick="ajaxAction($(this), 'users')"
                         class="@if(Auth::user()->isFollowing($user))unfollow @else()follow @endif"
                         data-id="{{ $user->id }}">

                        @if(auth()->user()->gender == \App\User::GENDER_MALE && $user->gender == \App\User::GENDER_FEMALE)
                            <div class="profile_user_btn @if(Auth::user()->isFollowing($user))btn-green unscribe @else  subscribe @endif">
                                <a>@if(Auth::user()->isFollowing($user)) Отписаться @else Подписаться @endif</a>
                            </div>
                        @elseif(auth()->user()->gender == \App\User::GENDER_FEMALE && $user->gender == \App\User::GENDER_MALE)
                            <div class="profile_user_btn @if(Auth::user()->isFollowing($user))btn-green unscribe @else  subscribe @endif">
                                <a>@if(Auth::user()->isFollowing($user)) Убрать из избранных @else Добавить в избранное @endif</a>
                            </div>
                        @endif

                    </div>
{{--                    <div onclick="ajaxAction($(this), 'users')" class="unfollow"--}}
{{--                         @if(!(Auth::user()->isFollowing($user))) hidden @endif--}}
{{--                         data-id="{{ $user->id }}">--}}

{{--                    <div class="profile_user_btn unscribe btn-green">--}}
{{--                        <a>Отписаться</a>--}}
{{--                    </div>--}}
{{--                    </div>--}}
                    <div class="btn-green write-message">
                        <a href="{{route('chat', $user->name)}}">Написать сообщение</a>
                    </div>
                @endif
            </div>

                <div class="profile_tabs">
                <ul>
                    <li class="{{ (request()->is("*user/$user->name")) ? 'active' : '' }}">
                        <a href="{{ route('profile', $user->name) }}">
                            <h5>Публикации</h5>
                            <span>{{$user->posts_count}}</span>
                        </a>
                    </li>
                    <li class="{{ (request()->is("*user/*/follows*")) ? 'active' : '' }}">
                        <a href="{{route('profile-follows', $user->name)}}">
                            <h5>Подписки</h5>
                            <span>{{$user->followings_count}}</span>
                        </a>
                    </li>
                    <li class="{{ (request()->is("*user/*/followers")) ? 'active' : '' }}">
                        <a href="{{ route('profile-followers', $user->name) }}">
                            <h5>Подписчики</h5>
                            <span>{{$user->followers_count}}</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="wrap-profile-content">
                @yield('inner')
            </div>
        </div>
    </section>


@endsection
@section('scripts')
    <script type="text/javascript">
        $( document ).ready(function() {
            $('.follow').click(function () {
                ajaxAction($(this), 'user');
            });
            $('.unfollow').click(function () {
                ajaxAction($(this), 'user');
            });
        });
        </script>
@endsection
