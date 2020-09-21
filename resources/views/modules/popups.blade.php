
    <!-- PopUps start -->
    @if(Route::current())
          
    <div class="wrap-pop-up" id="forgot-pass">
        <div class="pop-up-body">
            <div class="pop-up-body-authorization">
                <div class="close close-window"></div>
                <p class="infoPopUp">
                    На ваш е-майл будут отправлены дальнейшие инструкции для восстановления пароля
                </p>
                <h4>Введите E-mail</h4>
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="body-authorization-top">
                        <div class="inputs">
                            <input type="email" name="email" value="{{ old('email') }}"
                                   required autocomplete="email" autofocus placeholder="E-mail">
                        </div>
                        <button type="submit">Отправить</button>
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
    </div>
    <div class="wrap-pop-up" id="reset-pass"
         @if(Route::current() && Route::current()->getName() == 'password.reset') style="display:block !important;" @endif
    >
        <div class="pop-up-body">
            <div class="pop-up-body-authorization">
                <div class="close close-window"></div>
{{--                <p class="infoPopUp">--}}
{{--                    Введите ваш email и новый пароль--}}
{{--                </p>--}}
                <h4>Введите ваш email и новый пароль</h4>
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @if(Route::current() && Route::current()->getName() == 'password.reset')
                        <input type="hidden" name="token" value="{{ $token }}">
                    @endif
                    <div class="body-authorization-top">
                        <div class="inputs">
                            <input type="email" name="email" value="{{ old('email') }}"
                                   required autocomplete="email" autofocus placeholder="E-mail">
                            <input name="password" type="password" placeholder="Пароль">
                            <input name="password_confirmation" type="password" placeholder="Пароль ещё раз">

                        </div>
                        @error('password')--}}
                        <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                        @enderror
                        <button type="submit">Отправить</button>
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
    </div>

    <div class="wrap-pop-up" id="error-files">
        <div class="pop-up-body">
            <div class="pop-up-body-authorization">
                <div class="close close-window"></div>
                <p class="infoPopUp">
                    Вы можете добавить не более <span class="count_max"></span> фото
                </p>
                <div class="body-authorization-top">
                    <button class="close-window">Отмена</button>
                </div>
            </div>
        </div>
    </div>


    @endif
