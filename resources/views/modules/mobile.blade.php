<div class="wrap-top_mobile_head">
    <div class="container">
        <div class="top_mobile_head">
            <div class="top_mobile_head-logo">
                <a href="/">
                    <img src="/img/logo.svg" alt="" class="logo_img">
                </a>
            </div>
            @auth
            <div class="top_mobile_head-publish">
                <a href="{{route('add-post')}}">
                    <div class="top_mobile_head-publish-icon">
                        <img src="/img/publish_icon.svg" alt="">
                    </div>
                    <span>Опубликовать</span>
                </a>
            </div>
            @endauth

            @guest
            <div class="top_mobile_head-publish-authorization">
                <div class="wrap-authorization">
                    <div class="sign_in authorization-el unselectable signIn_open">
                        <div class="authorization-icon authorization-icon__sign_in"></div>
                        <span>Вход</span>
                    </div>
                    <div class="registration authorization-el unselectable registration_open">
                        <div class="authorization-icon authorization-icon__registration"></div>
                        <span>Регистрация</span>
                    </div>
                </div>
            </div>
            @endguest

        </div>
    </div>
</div>


<div class="wrap-menu-window-mobile">
    <div class="container">
        <div class="menu-window-mobile-top">
            <search></search>

            @auth
                <div class="user">
                    <a href="{{route('profile', Auth::user()->name)}}" class="user_first_a">
                        <div class="user-img" style="background-image: url(/storage/images/avatars/{{Auth::user()->avatar}});"></div>
                        <span>{{Auth::user()->name}}
                            <span>Перейти в профиль</span>
                        </span>
                    </a>
                    <a href="{{route('profile', Auth::user()->name)}}">
                        <div class="arr_link">
                            <img src="/img/arr_user.svg" alt="">
                        </div>
                    </a>
                </div>
            @endauth
        </div>
    </div>
    <div class="menu-window-mobile-maian">
        <div class="container">
            <ul class="menu-pages">
                <li class="{{ (request()->is('*popular*')) ? 'active' : '' }}"><a href="{{route('popular')}}">
                        <div class="icon_li best"></div>
                        <span>Лучшее</span>
                    </a></li>
                <li class="{{ (request()->is('*discussed*')) ? 'active' : '' }}">
                    <a href="{{route('discussed')}}">
                        <div class="icon_li discuss"></div>
                        <span>Обсуждаемое</span>
                    </a>
                </li>
                <li class="{{ (request()->is('*favorites*')) ? 'active' : '' }}">
                    <a href="{{route('favorites')}}">
                        <div class="icon_li bookmark"></div>
                        <span>Закладки</span>
                    </a></li>
                <li class="
                                {{ ((request()->is('*tags*')) && !(request()->is('*my/tags*'))) ? 'active' : '' }}">
                    <a href="{{route('tags')}}">
                        <div class="icon_li topics"></div>
                        <span>Теги</span>
                    </a></li>
                @can('add-tag')
                    <li class="
                                    {{ (request()->is('*tag/edit*')) ? 'active' : '' }}">
                        <a href="{{ route('tag-edit') }}">
                            <div class="icon_li topics"></div>
                            <span>Теги (админ)</span>
                        </a>
                    </li>
                @endcan
                @can('add-tag')
                    <li class="
                                    {{ (request()->is('*registered*')) ? 'active' : '' }}">
                        <a href="{{ route('registered') }}">
                            <img src="/img/user.svg" alt="">
                            <span> Пользователи (админ)</span>
                        </a>
                    </li>
                @endcan
                @can('approve-post')
                    <li class="
                                    {{ (request()->is('*unapproved*')) ? 'active' : '' }}">
                        <a href="{{ route('unapproved') }}">
                            <div class="icon_li notification">
                                <unapproved-count></unapproved-count>
                            </div>
                            <span>Модерация</span>
                        </a>
                    </li>
                @endcan

                <li><a href="/settings">
                        <div class="icon_li settings"></div>
                        <span>Настройки</span>
                    </a></li>
                <li><a onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();"
                       href="{{ route('logout') }}">
                        <div class="icon_li exit"></div>
                        Выйти
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="container">
        <ul class="site-info">
            <li>
                <a href="#">Условия</a>
            </li>
            <li>
                <a href="#">Политика конфиденциальности</a>
            </li>
            <li>
                <a href="#">Файлы cookie</a>
            </li>
            <li>
                <a href="/pages/support.html">Служба поддержки</a>
            </li>
        </ul>
    </div>
</div>