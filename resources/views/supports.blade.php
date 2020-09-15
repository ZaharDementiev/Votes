@extends('layouts.app')
@section('content')
    <section class="sec-balance">
        <div class="sec-balance__top">
            <h2 class="title title_margin">Баланс: {{$user->balance}} VirtCoin</h2>
            <div class="settings-content-el settings-content-el_small">
                <div class="settings-content-el-top unselectable">
                    <div class="settings-content-el-left">
                        <div class="settings-content-el-left-icon">
                            <img src="/img/icons/money1.svg" alt="">
                        </div>
                        <span>Вывести средства</span>
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
                                <h4>Выберите платежную систему</h4>
                                <img src="/img/iconsMoney.jpg" alt="">
                            </div>
                            <div class="input-el">
                                <h4>Введите сумму</h4>
                                <input type="text" class="inp-default">
                            </div>
                            <div class="input-el">
                                <h4>Введите номер счета</h4>
                                <input type="text" class="inp-default">
                            </div>
                        </div>
                        <div class="btn-green">
                            <button type="submit">Далее</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="sec-balance__content">
            <div class="tape-tabs">
                <ul>
                    <li><a href="#">Транзакции</a></li>
                    <li class="active"><a href="#">Поддержка</a></li>
                </ul>
            </div>
            <div class="sec-balance__content-body">
                <table class="table sec-balance__table">
                    <tbody>
                    <tr class="table__tr table__tr_main">
                        <td class="table__cell">
                            <div class="table__cell-body">Номер заявки</div>
                        </td>
                        <td class="table__cell">
                            <div class="table__cell-body">Дата</div>
                        </td>
                        <td class="table__cell">
                            <div class="table__cell-body">Сумма</div>
                        </td>
                        <td class="table__cell">
                            <div class="table__cell-body">Логин</div>
                        </td>
                        <td class="table__cell">
                            <div class="table__cell-body">Статус</div>
                        </td>
                        <td class="table__cell">
                            <div class="table__cell-body">Отзывы</div>
                        </td>
                    </tr>

                    @foreach($user->transactions as $transaction)
                    <tr class="table__tr">
                        <td class="table__cell">
                            <div class="table__cell-body">{{$transaction->id}}</div>
                        </td>
                        <td class="table__cell">
                            <div class="table__cell-body">{{ date("Y-m-d", strtotime($transaction->created_at))}}<br>{{ date("H:i:s", strtotime($transaction->created_at))}}</div>
                        </td>
                        <td class="table__cell">
                            <div class="table__cell-body">{{$transaction->amount}}</div>
                        </td>
                        <td class="table__cell">
                            <div class="table__cell-body">{{$transaction->receiver->name}}</div>
                        </td>
                        <td class="table__cell">
                            <div class="table__cell-body table__cell-body_left">Деньги переведены</div>
                        </td>
                        <td class="table__cell">
                            <div class="table__cell-body">
                                <a class="link btnReview" onclick="openFeedbackForm({{$transaction->id}})">Оставить отзыв</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
