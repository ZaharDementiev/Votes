<script>
    app.controller('loginCtrl', ['$scope', '$rootScope', '$http', function ($scope, $rootScope, $http){
        $scope.submit = function (event) {
            event.preventDefault();
            $scope.error = '';
            $http({
                url: '{{ url("login") }}',
                method: 'POST',
                data: $scope.form
            }).then(function (ret) {
                console.log(ret.data);
                if (ret.data.status == 'success') {
                    location.href = location.href;
                } else {
                    $scope.error = ret.data.message;
                }
            });
        }
    }]);
</script>

<style>
    #authModal .forgot{
        text-align: center;
        margin-bottom: 20px;
    }
    #authModal .forgot *{
        color: #757575;
        font-size: 14px;
    }    
</style>

<div id="authModal" class="modal" tabindex="-1" role="dialog" ng-controller="loginCtrl">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Авторизация</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form ng-submit="submit($event)" id="formLogin">                    
                    <div class="form-group">     
                        <input ng-model="form.email" placeholder="email или логин" type="text" class="form-control"  required autocomplete="email" autofocus>
                        <div class="error" ng-if="error.email" ng-cloak>@{{error.email}}</div>
                    </div>

                    <div class="form-group">
                        <input ng-model="form.password" placeholder="Пароль" type="password" class="form-control" autocomplete="current-password">
                        <div class="error" ng-if="error.password" ng-cloak>@{{error.password}}</div>
                    </div>

                    <div class="forgot form-group">
                        <a href="#" class="forgotPass_open">Забыли пароль?</a>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">Войти</button>
                    </div>

                    <div class="auth_btn" style="text-align: center; font-size: 16px; color: #444; font-weight: bold;">
                        <a data-toggle="modal" data-target="#registrationModal" href="#">Регистрация</a>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <p style="text-align: center; width: 100%;">или</p>
                <div class="socials">
                    <div class="social-el">
                        <a href="#">
                            <img src="/img/socials/twitter.png" alt="">
                        </a>
                    </div>
                    <div class="social-el">
                        <a href="#">
                            <img src="/img/socials/vk.png" alt="">
                        </a>
                    </div>
                    <div class="social-el">
                        <a href="#">
                            <img src="/img/socials/facebook.png" alt="">
                        </a>
                    </div>
                    <div class="social-el">
                        <a href="#">
                            <img src="/img/socials/googleplus.png" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>