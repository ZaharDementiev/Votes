    <script>
        app.controller('registrationCtrl', ['$scope', '$rootScope', '$http', function ($scope, $rootScope, $http) {
            $scope.form = {day:'0',month:'0',year:'0'};
            $scope.submit = function(event){
                event.preventDefault();
                
                $http({
                    url:'{{ url("register") }}',
                    method:'POST',
                    data:$scope.form
                }).then(function(ret){
                    if(ret.data.status == 'success'){
                        location.href = location.href;    
                    }                  
                });
            }
        }]);
    </script>
    
    
<div id="registrationModal" class="modal" tabindex="-1" role="dialog" ng-controller="registrationCtrl">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Регистрация</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form ng-submit="submit($event)" id="formLogin">                    
                    <span style="opacity: 0.5;font-size: 14px;">Логин может состоять только из латинских букв, символов . - и цифр.</span>
                    <div class="form-group">
                        <input class="form-control" name="name" type="text" placeholder="Логин" ng-model="form.name">
                    </div>
                    
                    <div class="form-group">
                        <input class="form-control" name="email" type="text" placeholder="E-mail" ng-model="form.email">
                    </div>
                    
                    <div class="form-group">
                        <input class="form-control" name="password" type="password" placeholder="Пароль" ng-model="form.password">
                    </div>
                    
                    <div class="form-group">
                        <input class="form-control" name="password_confirmation" type="password" placeholder="Пароль ещё раз" ng-model="form.password_confirmation">
                    </div>
                            
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 text-center">
                                <label>Male:<input name="gender" type="radio" value='1' ng-model="form.gender"></label>
                            </div>
                            <div class="col-sm-6 text-center">
                                <label>Female:<input name="gender" type="radio" value='2' ng-model="form.gender"></label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Дата рождения</label>
                        <div class="row">
                            <div class="col-sm-4">
                                <select class='form-control' ng-model='form.year'>
                                    <option value="0">Год</option>
                                    @for ($i = date("Y") - 16; $i > date("Y") - 100; $i--)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <select class='form-control' ng-model='form.month'>
                                    <option value="0">Месяц</option>
                                    @for ($i = 0; $i < 31; $i++)
                                        <option value='{{$i}}'>{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <select class='form-control' ng-model='form.day'>
                                    <option value="0">День</option>
                                    @for ($i = 0; $i < 31; $i++)
                                        <option value='{{$i}}'>{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="wrap-checkbox unselectable">
                            <label>
                                <div class="checkbox-el">
                                    <input type="checkbox" required="">
                                    <div class="checkbox"></div>
                                </div>
                                <span>Создавая аккаунт, я соглашаюсь с правилами сервиса<br> и даю согласие на обработку персональных данных</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">Создать аккаунт</button>
                    </div>
                    
                    <div class="auth_btn text-center">
                        <a data-toggle="modal" data-target="#authModal" href="#">Авторизация</a>
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
    
 <!--   <div class="wrap-pop-up" id="registration" ng-controller="registrationCtrl">
        <div class="pop-up-body">
            <div class="pop-up-body-authorization">
                <div class="close close-window"></div>
                <h4>Регистрация</h4>

                <div class="bg-danger error" id="registerfailedFull">
                    <i class="fa fa-times" aria-hidden="true"></i> Исправьте следующие ошибки:
                </div>

                <form id="formRegister" ng-submit="submit($event)">
                    <div class="body-authorization-top">
                        <span style="opacity: 0.5;font-size: 14px;">Логин может состоять только из латинских букв, символов . - и цифр.</span>
                        <div class="inputs">
                            <input name="name" type="text" placeholder="Логин" ng-model="form.name">
                            <input name="email" type="text" placeholder="E-mail" ng-model="form.email">
                            <input name="password" type="password" placeholder="Пароль" ng-model="form.password">
                            <input name="password_confirmation" type="password" placeholder="Пароль ещё раз" ng-model="form.password_confirmation">
                            
                            <div class="row">
                                <div class="col-sm-6 text-center">
                                    <label>Male:<input name="gender" type="radio" value='1' ng-model="form.gender"></label>
                                </div>
                                <div class="col-sm-6 text-center">
                                    <label>Female:<input name="gender" type="radio" value='2' ng-model="form.gender"></label>
                                </div>
                            </div>
                            
                            
                        </div>
                        <div class="wrap-checkbox unselectable">
                            <label>
                                <div class="checkbox-el">
                                    <input type="checkbox" required="">
                                    <div class="checkbox"></div>
                                </div>
                                <span>Создавая аккаунт, я соглашаюсь с правилами сервиса<br> и даю согласие на обработку персональных данных</span>
                            </label>
                        </div>
                        <button type="submit">Создать аккаунт</button>
                        <div class="auth_btn signIn_open">
                            <a href="#">Авторизация</a>
                        </div>
                    </div>
                    <div class="body-authorization-foot">
                        <p>или</p>
                        <div class="wrap-socials">
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
                </form>
            </div>
        </div>
    </div>-->
