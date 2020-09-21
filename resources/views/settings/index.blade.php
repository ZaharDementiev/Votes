@extends('layouts.app')
@section('content')
<script>
    app.controller('pageCtrl', ['$scope', '$rootScope', '$http', function ($scope, $rootScope, $http){
        $scope.login = {success:false, error:''};
        $scope.email = {success:false, error:''};
        $scope.password = {success:false, error:''};
        
        $scope.setActive = function(event){
            var parent = $(event.target).closest('.settings-content-el');
            
            if(parent.hasClass('active')){
                parent.removeClass('active');
            }else{
                parent.addClass('active');
            }
        }
        
        $scope.changeName = function(event){
            event.preventDefault();
            $scope.login.success = false;
            $scope.login.error = '';
            $http({
                url:'/settings/change-login',
                method:'POST',
                data:{name:$scope.name}
            }).then(function(ret){
                if(ret.data.status == 'success'){
                    $scope.login.success = true;
                }else{
                    $scope.error = ret.data.message;
                }
            });
        }
        
        $scope.changeEmail = function(event){
            event.preventDefault();
            $scope.email.success = false;
            $scope.email.error = '';
            $http({
                url:'/settings/change-email',
                method:'POST',
                data:{old:$scope.old_email,email:$scope.new_email, password: $scope.password}
            }).then(function(ret){
                if(ret.data.status == 'success'){
                    $scope.email.success = true;
                }else{
                    $scope.error = ret.data.message;
                }
            });
        }
        
        $scope.changePassword = function(event){
            event.preventDefault();
            $scope.password.success = false;
            $scope.password.error = '';
            $http({
                url:'/settings/change-password',
                method:'POST',
                data:{old:$scope.old_password,password:$scope.new_password, 'password-confirmation':$scope.confirm_password}
            }).then(function(ret){
                if(ret.data.status == 'success'){
                    $scope.password.success = true;
                }else{
                    $scope.error = ret.data.message;
                }
            });
        }
    }]);
</script>

<style>
    .settings-content-el.active .settings-content-el-body{
        display:block;
    }
</style>

<section class="sec-settings" ng-controller="pageCtrl">
    <div class="wrap-settings">
        <h2>Настройки</h2>
        <div class="wrap-settings-content">
            <div class="settings-content-el" >
                <div class="settings-content-el-top unselectable" ng-click="setActive($event)">
                    <div class="settings-content-el-left">
                        <div class="settings-content-el-left-icon">
                            <img src="/img/user.svg" alt="">
                        </div>
                        <span>Изменить имя</span>
                    </div>
                    <div class="settings-content-el-right">
                        <div class="arr_settigns">
                            <img src="/img/arr_blue.svg" alt="">
                        </div>
                    </div>
                </div>
                
                <div class="settings-content-el-body">
                    <form ng-submit="changeName($event)" action="{{route('login-change')}}">
                        <div class="form-group">
                            <label>Логин</label>
                            <input type="text" name="name" class="form-control" ng-model="name">
                            <div class="success" ng-if="login.success" ng-cloak>Вы успешно изменили ваше имя.</div>
                        </div>
                    
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="settings-content-el">
                <div class="settings-content-el-top unselectable" ng-click="setActive($event)">
                    <div class="settings-content-el-left">
                        <div class="settings-content-el-left-icon">
                            <img src="/img/settings/mail.svg" alt="">
                        </div>
                        <span>Изменить e-mail</span>
                    </div>
                    <div class="settings-content-el-right">
                        <div class="arr_settigns">
                            <img src="/img/arr_blue.svg" alt="">
                        </div>
                    </div>
                </div>
                <div class="settings-content-el-body">
                    <form ng-submit="changeEmail($event)" method="POST" action="{{route('email-change')}}">
                        <div class="form-group">                            
                            <label>Текущий e-mail</label>
                            <input type="email" name="old" class="form-control" ng-model="old_email">
                        </div>

                        <div class="form-group">
                            <label>Новый e-mail</label>
                            <input name="email" type="email" class="form-control" ng-model="new_email">
                        </div>

                        <div class="form-group">
                            <label>Пароль для подтверждения</label>
                            <input type="password" name="password" class="form-control" ng-model="password">
                        </div>
                        
                        <div class="success" ng-if="email.success" ng-cloak>Вы успешно изменили ваш Email.</div>
                        
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="settings-content-el">
                <div class="settings-content-el-top unselectable" ng-click="setActive($event)">
                    <div class="settings-content-el-left">
                        <div class="settings-content-el-left-icon">
                            <img src="/img/settings/pass.svg" alt="">
                        </div>
                        <span>Сменить пароль</span>
                    </div>
                    <div class="settings-content-el-right">
                        <div class="arr_settigns">
                            <img src="/img/arr_blue.svg" alt="">
                        </div>
                    </div>
                </div>
                
                <div class="settings-content-el-body">
                    <form ng-submit="changePassword($event)" method="POST" action="{{route('password-change')}}">
                        <div class="form-group">
                            <label>Текущий пароль</label>
                            <input type="password" name="old" class="form-control" ng-model="old_password">
                        </div>
                        
                        <div class="form-group">
                            <label>Новый пароль</label>
                            <input type="password" name="password" class="form-control" ng-model="new_password">
                        </div>
                        
                        <div class="form-group">
                            <label>Новый пароль еще раз</label>
                            <input type="password" name="password-confirmation" class="form-control" ng-model="confirm_password">
                        </div>
                        
                        <div class="success" ng-if="email.success" ng-cloak>Вы успешно изменили ваш Email.</div>
                        
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
            
            
            @if(Auth::user()->gender == 2)
                <script>
                    app.controller('pricesCtrl', ['$scope', '$rootScope', '$http', function ($scope, $rootScope, $http){
                        $scope.form = {message:{{(int)Auth::user()->message_price / 100}},photo:{{(int)Auth::user()->photo_price / 100}},video:{{(int)Auth::user()->video_price / 100}}};
                        
                        $scope.submit = function(){
                            $http({
                                url:'/settings/change_prices',
                                method:'POST',
                                data: $scope.form
                            }).then(function(ret){
                                console.log(ret);
                            });
                        }
                    }]);
                </script>
                <div class="settings-content-el" ng-controller="pricesCtrl">
                    <div class="settings-content-el-top unselectable" ng-click="setActive($event)">
                        <div class="settings-content-el-left">
                            <div class="settings-content-el-left-icon">
                                <img src="/img/settings/pass.svg" alt="">
                            </div>
                            <span>Редактировать цены</span>
                        </div>
                        <div class="settings-content-el-right">
                            <div class="arr_settigns">
                                <img src="/img/arr_blue.svg" alt="">
                            </div>
                        </div>
                    </div>

                    <div class="settings-content-el-body">
                        <form ng-submit="submit($event)">
                            <div class="form-group">
                                <label>Цена за сообщение</label>
                                <input type="text" class="form-control" ng-model="form.message">
                            </div>

                            <div class="form-group">
                                <label>Цена за фотографию</label>
                                <input type="text" class="form-control" ng-model="form.photo">
                            </div>

                            <div class="form-group">
                                <label>Цена за видео</label>
                                <input type="text" class="form-control" ng-model="form.video">
                            </div>

                            <div class="success" ng-if="email.success" ng-cloak>Вы успешно изменили Цены.</div>

                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">Сохранить</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
