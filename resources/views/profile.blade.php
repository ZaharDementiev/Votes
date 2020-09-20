@extends('layouts.app')
@section('content')
    @php $owner = $user->id == Auth::id() @endphp
    <section class="sec-profile">
        <div class="wrap-profile">

                <div class="profile_user userPage">
                    <div class="profile_user_img" style="background-image: url(/storage/images/avatars/{{ $user->avatar}});"></div>
                    <div class="profile_user_name">
                        <p>{{$user->name}} {{$user->age}}</p>
                    </div>
                    @if(auth()->user()->gender == \App\User::GENDER_MALE && $user->gender == \App\User::GENDER_FEMALE)
                        <div class="descrp-block descrp-block_center">
                            <div class="descrp-block__el">
                                <span class="descrp-block__name">Рейтинг: </span>
                                <span class="descrp-block__val">{{$user->rating}}</span>
                            </div>
                            <div class="descrp-block__el">
                                <span class="descrp-block__name">Оценка: </span>
                                <span class="descrp-block__val">4,5</span>
                            </div>
                        </div>
                        <div class="rating rating_center userPage_rating">
                            <div class="rating__el rating-el rating-el_active"></div>
                            <div class="rating__el rating-el rating-el_active"></div>
                            <div class="rating__el rating-el rating-el_active"></div>
                            <div class="rating__el rating-el rating-el_active"></div>
                            <div class="rating__el rating-el"></div>
                        </div>
                    @endif

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
                    @if(auth()->user()->gender == \App\User::GENDER_MALE && $user->gender == \App\User::GENDER_FEMALE)

                    <div class="settings-content-el settings-content-el_small">
                        <div class="settings-content-el-top unselectable">
                            <div class="settings-content-el-left">
                                <div class="settings-content-el-left-icon">
                                    <img src="/img/icons/money.svg" alt="">
                                </div>
                                <span>Поддержать и отправить FVC</span>
                            </div>
                            <div class="settings-content-el-right">
                                <div class="arr_settigns">
                                    <img src="/img/arr_blue.svg" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="settings-content-el-body">
                            <form class="form-settigns-el">
                                <div class="inputs">
                                    <div class="input-el">
                                        <h4>Введите сумму</h4>
                                        <input type="text" class="inp-default">
                                    </div>
                                </div>
                                <div class="btn-green">
                                    <button type="submit">Далее</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                    @if(request()->is("*user/$user->name"))
                        <div class="profile-descrp userPage__descrp">
                        <div class="profile-descrp__block">
                            <div class="profile-descrp__title title title_small">О себе</div>
                            <p class="profile-descrp__text">{{$user->about}}</p>
                        </div>
                        <div class="profile-descrp__block">
                            <div class="profile-descrp__title title title_small">Вирт общение:</div>
                            <ul class="profile-descrp-ul profile-descrp__descrp">
                                <li class="profile-descrp__text"><span>-</span>переписка</li>
                                <li class="profile-descrp__text"><span>-</span>фото архив</li>
                                <li class="profile-descrp__text"><span>-</span>голосовые сообщения</li>
                                <li class="profile-descrp__text"><span>-</span>видео архив</li>
                                <li class="profile-descrp__text"><span>-</span>видеоособщения</li>
                                <li class="profile-descrp__text"><span>-</span>фото на заказ</li>
                                <li class="profile-descrp__text"><span>-</span>видеозвонок</li>
                                <li class="profile-descrp__text"><span>-</span>видео на заказ</li>
                            </ul>
                        </div>
                        <div class="profile-descrp__block">
                            <div class="profile-descrp__title title title_small">Контакты:</div>
                            <div class="block-contacts profile-descrp__contacts">
                                @if($user->whatsapp != null)
                                    <div class="block-contacts__el contacts-el">
                                        <img src="/img/icons/whatsapp.svg" alt="" class="contacts-el__icon">
                                        <div class="contacts-el__title">Whatsapp: </div>
                                        <div class="contacts-el__text">{{$user->whatsapp}}</div>
                                    </div>
                                @endif
                                @if($user->viber != null)
                                    <div class="block-contacts__el contacts-el">
                                        <img src="/img/icons/viber.svg" alt="" class="contacts-el__icon">
                                        <div class="contacts-el__title">Viber: </div>
                                        <div class="contacts-el__text">{{$user->viber}}</div>
                                    </div>
                                @endif
                                @if($user->telegram != null)
                                    <div class="block-contacts__el contacts-el">
                                        <img src="/img/icons/tel.svg" alt="" class="contacts-el__icon">
                                        <div class="contacts-el__title">Telegram: </div>
                                        <div class="contacts-el__text">{{$user->telegram}}</div>
                                    </div>
                                @endif
                                @if($user->skype != null)
                                    <div class="block-contacts__el contacts-el">
                                        <img src="/img/icons/skype.svg" alt="" class="contacts-el__icon">
                                        <div class="contacts-el__title">Skype: </div>
                                        <div class="contacts-el__text">{{$user->skype}}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
            </div>

            <div class="profile_tabs">
                <ul>
                    <li class="{{ (request()->is("*user/$user->name")) ? 'active' : '' }}">
                        <a href="{{ route('profile', $user->name) }}">
                            @if($user->gender == \App\User::GENDER_FEMALE)
                                <h5>Флирты</h5>
                            @elseif($user->gender == \App\User::GENDER_MALE)
                                <h5>Публикации</h5>
                            @endif
                            <span>{{$user->posts_count}}</span>
                        </a>
                    </li>
                    <li class="{{ (request()->is("*user/*/follows*")) ? 'active' : '' }}">
                        <a href="{{route('profile-follows', $user->name)}}">
                            @if($user->gender == \App\User::GENDER_FEMALE)
                                <h5>В избранном</h5>
                            @elseif($user->gender == \App\User::GENDER_MALE)
                                <h5>Подписки</h5>
                            @endif
                            <span>{{$user->followings_count}}</span>
                        </a>
                    </li>
                    <li class="{{ (request()->is("*user/*/followers")) ? 'active' : '' }}">
                        <a href="{{ route('profile-followers', $user->name) }}">
                            <h5>Подписчики</h5>
                            <span>{{$user->followers_count}}</span>
                        </a>
                    </li>
                    <li>
                        <a href="profile-followers.html">
                            <h5>Отзывы</h5>
                            <span>12 | <span class="text-red">1</span></span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Если акк бабы - Флирты, если мужика - Публикации !-->
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
