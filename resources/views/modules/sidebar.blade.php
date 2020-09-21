 <div class="wrap-left-menu">
    <div class="left-menu-content">
        <a class="logo" href="/">
            <img src="/img/logo.svg" alt="" class="logo_img">
        </a>
        <nav class="menu">
            <ul class="menu-pages">

                <li @if(Route::current()) class="{{ Route::current()->getName() == 'live' ? 'active' : '' }} mobShow live" @endif><a href="{{ route('live') }}">
                        <div class="icon_li main"></div>
                        <span>Флирт</span>
                    </a>
                </li>
                
                @auth
                    <li class="{{ (request()->is('*virt*')) ? 'active' : '' }} mobShow tape">
                        <a href="{{ route('virt_girls') }}">
                            <div class="icon_li tape"></div>
                            <span>Вирт</span>
                        </a>
                    </li>
                    
                    <li class="{{ (request()->is('*my/tags*')) ? 'active' : '' }}">
                        <a href="{{route('my-tags')}}">
                            <div class="icon_li best"></div>
                            <span>Мои подписки</span>
                        </a>
                    </li>
                    
                    <!--<li class="{{ (request()->is('*discussed*')) ? 'active' : '' }}">
                        <a href="{{route('discussed')}}">
                            <div class="icon_li discuss"></div>
                            <span>Обсуждаемое</span>
                        </a>
                    </li>-->
                    
                    <li class=" {{ (request()->is('*notifications*')) ? 'active' : '' }} mobShow notification">
                        <a href="{{ route('notifications') }}">
                            <div class="icon_li notification">
                              <notifications-count :user="{{ Auth::user() }}"></notifications-count>
                            </div>
                            <span>Уведомления</span>
                        </a>
                    </li>
                    
                    <li class="mobShow messages {{ (request()->is('*chat*')) || (request()->is('*dialogs*')) ? 'active' : '' }}">
                        <a href="{{ route('dialogs') }}">
                            <div class="icon_li messages">
                                <messages-count :user="{{Auth::user()}}"></messages-count>
                            </div>
                            <span>Сообщения</span>
                        </a>
                    </li>
                    
                    <li class="{{ (request()->is('*favorites*')) ? 'active' : '' }}">
                        <a href="{{ route('favorites') }}">
                            <div class="icon_li bookmark"></div>
                            <span>Закладки</span>
                        </a>
                    </li>
                    
                    <li class="
                        {{ ((request()->is('*tags*')) && !(request()->is('*my/tags*'))) ? 'active' : '' }}">
                        <a href="{{ route('tags') }}">
                            <div class="icon_li topics"></div>
                            <span>Теги</span>
                        </a>
                    </li>
                @endauth
                
                @guest
                    <li class="{{ (request()->is('*my*')) ? 'active' : '' }} mobShow tape">
                        <a href="{{ route('my-tags') }}" onclick="preventDefault() " class="signIn_open">
                            <div class="icon_li tape">
                            </div>
                            <span>Моя лента </span>
                        </a>
                    </li>
                    
                    <li class="{{ (request()->is('*popular*')) ? 'active' : '' }}">
                        <a href="{{route('popular')}}">
                            <div class="icon_li best"></div>
                            <span>Лучшее</span>
                        </a>
                    </li>
                    
                    <!--<li class="{{ (request()->is('*discussed*')) ? 'active' : '' }}">
                        <a href="{{route('discussed')}}">
                            <div class="icon_li discuss"></div>
                            <span>Обсуждаемое</span>
                        </a>
                    </li>-->
                    
                    <li class=" {{ (request()->is('*notifications*')) ? 'active' : '' }} mobShow notification">
                        <a href="{{ route('notifications') }}"
                           onclick="preventDefault()" class="signIn_open">
                            <div class="icon_li notification">
                            </div>
                            <span>Уведомления</span>
                        </a>
                    </li>
                    
                    <li class="mobShow messages
                        {{ (request()->is('*chat*')) || (request()->is('*dialogs*')) ? 'active' : '' }}">
                        <a href="{{ route('dialogs') }}" onclick="preventDefault()" class="signIn_open">
                            <div class="icon_li messages">
                            </div>
                            <span>Сообщения</span>
                        </a>
                    </li>
                    
                    <li class="{{ (request()->is('*favorites*')) ? 'active' : '' }}">
                        <a href="{{ route('favorites') }}" onclick="preventDefault()" class="signIn_open">
                            <div class="icon_li bookmark"></div>
                            <span>Закладки</span>
                        </a>
                    </li>
                    
                    <li class="
                        {{ ((request()->is('*tags*')) && !(request()->is('*my/tags*'))) ? 'active' : '' }}">
                        <a href="{{ route('tags') }}">
                            <div class="icon_li topics"></div>
                            <span>Теги</span>
                        </a>
                    </li>
                @endguest    
                
                
                @can('add-tag')
                    <li class="
                        {{ (request()->is('*tag/edit*')) ? 'active' : '' }}">
                        <a href="{{ route('tag-edit') }}">
                            <div class="icon_li topics"></div>
                            <span>Теги (админ)</span>
                        </a>
                    </li>
                
                    <li class="
                        {{ (request()->is('*registered*')) ? 'active' : '' }}">
                        <a href="{{ route('registered') }}">
                            <div class="icon_li topics"></div>
                            <span>Пользователи (админ)</span>
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
                
                <li class="mobMenu mobBurger">
                    <a>
                        <div class="icon_li mobBurger"></div>
                        <span>Меню</span>
                    </a>
                </li>
            </ul>
        </nav>
        @auth
            <div class="link_post btn-green">
                <a href="{{ route('add-post') }}">Опубликовать</a>
            </div>
        @endauth
        @guest
            <div class="link_post btn-green">
                <a href="{{ route('add-post') }}" onclick="preventDefault()" class="signIn_open">Опубликовать</a>
            </div>
        @endguest
        <ul class="site-info site-info_desctop">
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

        <p style="margin-top: 5px" class="foot-info-site">© 2019 Lsecrets.ru - лента женских секретов, советов, вопросов и ответов. </p>
        <p style="margin-top: 5px" class="foot-info-site">Контакты: info@lsecrets.ru</p>
    </div>
</div>
