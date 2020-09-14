@extends('layouts.app')
@section('content')
    <section class="sec-settings">
        <div class="wrap-settings">
            <h2>Настройки</h2>
            <div class="wrap-settings-content">
                <div class="settings-content-el">
                    <div class="settings-content-el-top unselectable">
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
                        <form action="{{route('login-change')}}" method="POST" class="form-settigns-el">
                            @csrf
                            <div class="inputs">
                                <div class="input-el">
                                    <h4>Логин</h4>
                                    <input type="text" name="name" class="inp-default">
                                </div>
                            </div>
                            <div class="btn-green">
                                <button type="submit">Сохранить</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="settings-content-el">
                    <div class="settings-content-el-top unselectable">
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
                        <form method="POST" action="{{route('email-change')}}" class="form-settigns-el">
                            @csrf
                            <div class="inputs">
                                <div class="input-el">
                                    <h4>Текущий e-mail</h4>
                                    <input type="email" name="old" class="inp-default">
                                </div>
                                <div class="input-el">
                                    <h4>Новый e-mail</h4>
                                    <input name="email" type="email" class="inp-default">
                                </div>
                                <div class="input-el">
                                    <h4>Пароль для подтверждения</h4>
                                    <input type="password" name="password" class="inp-default error">
                                </div>
                            </div>
                            <div class="btn-green">
                                <button type="submit">Сохранить</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="settings-content-el">
                    <div class="settings-content-el-top unselectable">
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
                        <form method="POST" action="{{route('password-change')}}" class="form-settigns-el">
                            <div class="inputs">
                                <div class="input-el">
                                    <h4>Текущий пароль</h4>
                                    <input type="password" name="old" class="inp-default">
                                </div>
                                <div class="input-el">
                                    <h4>Новый пароль</h4>
                                    <input type="password" name="password" class="inp-default">
                                </div>
                                <div class="input-el">
                                    <h4>Новый пароль еще раз</h4>
                                    <input type="password" name="password-confirmation" class="inp-default">
                                </div>
                            </div>
                            <div class="btn-green">
                                <button type="submit">Сохранить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
