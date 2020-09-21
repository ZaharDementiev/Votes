<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('modules.head')       
</head>
<body ng-app="app" ng-controller="mainCtrl">    
    <script>
        var app = angular.module("app", []);

        app.controller('mainCtrl', ['$scope', '$rootScope', '$compile', '$http', function ($scope, $rootScope, $compile, $http) {                                
                
            /*$scope.closePopUp = function(wrap) {
                wrap.fadeOut(400);
                $('body').removeClass('noscroll');
                return false;
            }

            $scope.openPopUp = function(wrap) {
                $('.wrap-pop-up').fadeOut(100);
                $(wrap).fadeIn(300);
                $('body').addClass('noscroll');
                return false;
            }

            $('.registration_open').on('click', function() {
                
                $scope.openPopUp($('#registration'));
                return false;
            });*/

        }]);
    </script>
    @if(env('APP_ENV')=='production')
        @include('modules.yandex_metrika')
    @endif
        
    @include('modules.loader')    
    @include('modules.mobile')

    @auth
        @if(auth()->user()->email_verified_at == null)
            <div class="confirm-email">
                <div class="close close-mail"></div>
                <p>Пожалуйста подтвердите свой E-mail</p>
            </div>
        @endif
    @endauth

    @if(session()->has('notify'))
        <div class="confirm-email">
            <div class="close close-mail"></div>
            <p>{{ session()->get('notify') }}</p>
        </div>
    @endif

    <div id="wrap-content">
        <div class="container" id="app" >
            <div id="wrap-content-content">
                <div class="wrap-main-content">

                    @include('modules.sidebar')
                    <div class="main-content">
                        @include('modules.header')

                        <section class="sec-tape" >
                            @yield('content')
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @guest
        @include('modules.registration')
        @include('modules.popups')
    @endguest

    @yield('scripts')

    <!--<script src="/js/jquery.fancybox.min.js"></script>
    <script src="/js/select2.min.js"></script>
    <script src="/js/slick.min.js"></script>-->
    
    <script>    
        $(document).ready(function(){
            $("#registerfailedFull").slideUp();
            $('#loginfailedFull').slideUp();

            var loginForm = $("#formLogin");
            var registerForm = $("#formRegister");
            console.log(registerForm);
            let loginHtml = $('#formLogin').html();
            let registerHtml = $('#formRegister').html();
            loginForm.submit(function (e) {
                e.preventDefault();
                var formData = loginForm.serialize();
                $.ajax({
                    url: '{{ url("login") }}',
                    type: 'POST',
                    data: formData,
                    {{-- Send CSRF Token over ajax --}}
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: function () {
                        $('.wrapper-load').fadeIn();

                        $("#loginfailedFull").slideUp();

                        $("#formLogin").html('<i class="fa fa-spinner fa-spin fa-fw"></i>');
                        $("#formLogin").prop("disabled", true);
                    },
                    success: function (data) {
                        window.location.reload(true);
                    },
                    error: function (data) {
                        console.log(loginHtml);
                        $("#loginfailedFull").slideDown();
                        $("#formLogin").prop("disabled", false);
                        $('#formLogin').html(loginHtml);
                        $('.wrapper-load').fadeOut(300);

                    }
                });
            });

            registerForm.submit(function (e) {
                e.preventDefault();
                var formData = registerForm.serialize();
                $.ajax({
                    url: '{{ url("register") }}',
                    type: 'POST',
                    data: formData,
                    {{-- Send CSRF Token over ajax --}}
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: function () {
                        $("#registerfailedFull").slideUp();
                        // $("#formRegister").html('<i class="fa fa-spinner fa-spin fa-fw"></i>');
                        $('.wrapper-load').fadeIn();
                        $("#formRegister").prop("disabled", true);
                    },
                    error: function (data) {
                        $("#registerfailedFull").html('');
                        $("#registerfailedFull").append('<ul>');
                        let obj = jQuery.parseJSON( data.responseText );
                        let values = Object.values(obj.errors);

                        for (value of values) {
                            $("#registerfailedFull").append('<li>' + value[0] + '</li>');
                        }

                        $("#registerfailedFull").slideDown();
                        $("#formRegister").prop("disabled", false);
                        $('#formRegister').html(registerHtml);
                        $("#registerfailedFull").append('</ul>');
                        $('.wrapper-load').fadeOut(300);
                    },
                    success: function (data) {
                        @if(env('APP_ENV')=='production')
                            ym(55702444, 'reachGoal', 'register');
                        @endif

                        setTimeout(function () {window.location.reload(true)}, 500);
                    }
                });
            });
        });
    </script>
</body>
</html>
