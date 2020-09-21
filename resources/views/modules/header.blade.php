<script>
    app.controller('headerCtrl', ['$scope', '$rootScope', '$http', function ($scope, $rootScope, $http) {            
        $scope.tags = [];
        $scope.posts = [];
        $scope.query = '';
        
        $('html,body').mouseup(function (e){
            var div = $('.main-content-top .user');
            if (!div.is(e.target) && div.has(e.target).length === 0) {
                
                $scope.closeUserMenu();
                return false;
            }
        });
        
        $scope.activateMenu = function(e){
            let wrap_all = $(e.target).closest('.user');
            if ( wrap_all.hasClass('open') ) {
                wrap_all.removeClass('open');
                $scope.closeUserMenu();
            }else{
                wrap_all.addClass('open');
                wrap_all.find('.user-menu').fadeIn(200);
            }
        }
        
        $scope.closeUserMenu = function() {
            $('.user').removeClass('open');
            $('.user-menu').removeClass('open');
            $('.user-menu').fadeOut(200);
        }
        
        $scope.fetchResults = function(e){
            console.log($scope.query.length);
            setTimeout(() => {
                if ($scope.query.length > 1) {
                    console.log($scope.query);
                    $http({
                        url:'/public/search?query='+$scope.query
                    }).then(function(ret){
                        $scope.tags = ret.data[0];
                        $scope.posts = ret.data[1];
                    });
                }
            }, 600);
        }
    }]);
</script>

<div class="main-content-top" ng-controller="headerCtrl">
    <div class="search">
        <input type="text" ng-keyup="fetchResults($event)" placeholder="Поиск по сайту.." ng-model="query">
        <div class="search-btn" style="background-image: url('/img/search.svg');"></div>
        <ul class="search-results" id="search-res" v-show="query.length > 1">
            <li ng-if="tags.length" ng-repeat="tag in tags" ng-cloak>
                <a href="/tag/@{{tag.slug}}">
                    <div class="icon" style="background-image: url(/img/icons_li/topics.svg);"></div>
                    @{{tag.name}}
                </a>
            </li>
            <li ng-if="posts.length > 0" ng-repeat="post in posts" ng-cloak>
                <a href="/posts/@{{post.slug}}">
                    <div class="icon" style="background-image: url(/img/icons_li/main.svg);"></div>
                    @{{post.title}}
                </a>
            </li>
        </ul>
    </div>
    @auth
        <div class="user unselectable" ng-click="activateMenu($event)">
            <div class="user-img" style="background-image: url(/storage/images/avatars/{{Auth::user()->avatar}});"></div>
            <span>{{Auth::user()->name}}</span>
            <div class="user-menu">
                <ul>
                    <li>
                        <a href="{{ route('profile', Auth::user()->name) }}">
                            <img src="/img/user.svg" alt="">
                            <span>Мой профиль</span>
                        </a>
                    </li>
                    <li>
                        <a href="/settings">
                            <img src="/img/settigns_icons/settings.svg" alt="">
                            <span>Настройки</span>
                        </a>
                    </li>
                    
                    @if(Auth::user()->gender == 2)
                        <li>
                            <a href="/goods/">
                                <img src="/img/settigns_icons/settings.svg" alt="">
                                <span>Мои товары</span>
                            </a>
                        </li>
                    @endif
                    
                    <li><a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" href="{{ route('logout') }}">
                            <img src="/img/settigns_icons/logout.svg" alt="">
                            <span>Выйти</span>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="user-arrow unselectable"></div>
        </div>
    @endauth

    @guest
        <div class="wrap-authorization desctop">
            <div class="sign_in authorization-el unselectable" data-toggle="modal" data-target="#authModal">
                <div class="authorization-icon authorization-icon__sign_in"></div>
                <span>Вход</span>
            </div>
            
            <div class="registration authorization-el unselectable" data-toggle="modal" data-target="#registrationModal">
                <div class="authorization-icon authorization-icon__registration"></div>
                <span>Регистрация</span>
            </div>
        </div>
    @endguest


</div>