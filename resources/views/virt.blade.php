@extends('layouts/app')
@section('content')
    @php $tags = \App\Tag::all() @endphp
{{--    <section class="sec-tape">--}}
{{--        <div class="wrap-topic">--}}

{{--            <div class="settings-content-el">--}}
{{--                <div class="settings-content-el-top unselectable">--}}
{{--                    <div class="settings-content-el-left">--}}
{{--                        <div class="settings-content-el-left-icon">--}}
{{--                            <img src="/img/icons/searchDoc.svg" alt="">--}}
{{--                        </div>--}}
{{--                        <span>Найти вирт</span>--}}
{{--                    </div>--}}
{{--                    <div class="settings-content-el-right">--}}
{{--                        <div class="arr_settigns">--}}
{{--                            <img src="/img/arr_blue.svg" alt="">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="settings-content-el-body">--}}
{{--                    <form class="form-settigns-el">--}}
{{--                        <label class="wrap-radio__el radio-el form-settigns-el__el">--}}
{{--                            <span class="radio-el__text">Online</span>--}}
{{--                            <span class="radio-el__inp radio">--}}
{{--                          <input type="checkbox" name="sex" class="radio__inp">--}}
{{--                          <span class="radio__dec">--}}
{{--                            <span class="radio__dec-circle"></span>--}}
{{--                          </span>--}}
{{--                        </span>--}}
{{--                        </label>--}}
{{--                        <div class="form-settigns-el__el">--}}
{{--                            <div class="wrap-range">--}}
{{--                                <div class="form-settigns-el__title title title_small">Возраст</div>--}}
{{--                                <div class="rangeSlider wrap-range__range">--}}
{{--                                    <span class="irs irs--flat js-irs-0"><span class="irs"><span class="irs-line" tabindex="0"></span><span class="irs-min" style="">18</span><span class="irs-max" style="">99</span><span class="irs-from">0</span><span class="irs-to">0</span><span class="irs-single">0</span></span><span class="irs-grid"></span><span class="irs-bar"></span><span class="irs-shadow shadow-from"></span><span class="irs-shadow shadow-to"></span><span class="irs-handle from"><i></i><i></i><i></i></span><span class="irs-handle to type_last"><i></i><i></i><i></i></span></span><input type="text" class="rangeSlider__inp js-range-slider irs-hidden-input" name="my_range" value="" tabindex="-1" readonly="">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="select form-settigns-el__el">--}}
{{--                            <div class="form-settigns-el__title title title_small">Теги</div>--}}
{{--                            <select class="select_jq select2-hidden-accessible" data-placeholder="выберите не более 4-х тегов, начните вводить текст" multiple="" data-select2-id="1" tabindex="-1" aria-hidden="true">--}}
{{--                                @foreach($tags as $tag)--}}
{{--                                    <option>{{$tag->name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <div class="btn-green">--}}
{{--                            <button type="submit">Сохранить</button>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}


{{--            <div class="articles-users">--}}
{{--                @foreach($women as $woman)--}}
{{--                    <div class="articles-users__el article-user article-user_vip">--}}
{{--                        <div class="article-user__top">--}}
{{--                            <a href="#" class="article-user__img userpic">--}}
{{--                                <img src="{{$woman->avatar}}" alt="" class="userpic__img">--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                        <div class="article-user__body">--}}
{{--                            <a href="#" class="article-user__name">{{$woman->name}}</a>--}}
{{--                            <div class="article-user__info">Оценка: <span class="article-user__info_bold">4,5</span></div>--}}
{{--                            <div class="rating">--}}
{{--                                <div class="rating__el rating-el rating-el_active"></div>--}}
{{--                                <div class="rating__el rating-el rating-el_active"></div>--}}
{{--                                <div class="rating__el rating-el rating-el_active"></div>--}}
{{--                                <div class="rating__el rating-el rating-el_active"></div>--}}
{{--                                <div class="rating__el rating-el"></div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}


    <section class="sec-topic">
        <div class="wrap-topic">
            <div class="settings-content-el active">
                <div class="settings-content-el-top unselectable">
                    <div class="settings-content-el-left">
                        <div class="settings-content-el-left-icon">
                            <img src="/img/icons/searchDoc.svg" alt="">
                        </div>
                        <span>Найти вирт</span>
                    </div>
                    <div class="settings-content-el-right">
                        <div class="arr_settigns">
                            <img src="/img/arr_blue.svg" alt="">
                        </div>
                    </div>
                </div>
                <div class="settings-content-el-body" style="display: block;">
                    <form class="form-settigns-el">
                        <label class="wrap-radio__el radio-el form-settigns-el__el">
                            <span class="radio-el__text">Online</span>
                            <span class="radio-el__inp radio">
                          <input type="checkbox" name="sex" class="radio__inp">
                          <span class="radio__dec">
                            <span class="radio__dec-circle"></span>
                          </span>
                        </span>
                        </label>
                        <div class="form-settigns-el__el">
                            <div class="wrap-range">
                                <div class="form-settigns-el__title title title_small">Возраст</div>
                                <div class="rangeSlider wrap-range__range">
                                    <span class="irs irs--flat js-irs-0"><span class="irs"><span class="irs-line" tabindex="0"></span><span class="irs-min" style="visibility: hidden;">18</span><span class="irs-max" style="visibility: visible;">99</span><span class="irs-from" style="visibility: visible; left: 2.26675%;">20</span><span class="irs-to" style="visibility: visible; left: 14.1394%;">30</span><span class="irs-single" style="visibility: hidden; left: 5.64648%;">20 — 30</span></span><span class="irs-grid"></span><span class="irs-bar" style="left: 4.29046%; width: 11.8726%;"></span><span class="irs-shadow shadow-from" style="display: none;"></span><span class="irs-shadow shadow-to" style="display: none;"></span><span class="irs-handle from" style="left: 2.37452%;"><i></i><i></i><i></i></span><span class="irs-handle to type_last" style="left: 14.2471%;"><i></i><i></i><i></i></span></span><input type="text" class="rangeSlider__inp js-range-slider irs-hidden-input" name="my_range" value="" tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="select form-settigns-el__el">
                            <div class="form-settigns-el__title title title_small">Теги</div>
                            <select class="select_jq select2-hidden-accessible" data-placeholder="выберите не более 4-х тегов, начните вводить текст" multiple="" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                @foreach($tags as $tag)
                                    <option>{{$tag->name}}</option>
                                @endforeach
                            </select>
                        </div>
{{--                        @if(!auth()->user()->vip)--}}
{{--                        <div class="vip-block">--}}
{{--                            <div class="vip-block__icon"></div>--}}
{{--                            <div class="vip-block__text">Доступно для VIP</div>--}}
{{--                        </div>--}}
{{--                        @else--}}
                            <div class="btn-green">
                                <button type="submit">Сохранить</button>
                            </div>
{{--                        @endif--}}
                    </form>
                </div>
            </div>
            <div class="articles-users">
                @foreach($women as $woman)
                <div class="articles-users__el article-user article-user_vip">
                    <div class="article-user__top">
                        <a href="#" class="article-user__img userpic">
                            <img src="{{$woman->avatar}}" alt="" class="userpic__img">
                        </a>
                    </div>
                    <div class="article-user__body">
                        <a href="#" class="article-user__name">{{$woman->name}}</a>
                        <div class="article-user__info">Оценка: <span class="article-user__info_bold">4,5</span></div>
                        <div class="rating">
                            <div class="rating__el rating-el rating-el_active"></div>
                            <div class="rating__el rating-el rating-el_active"></div>
                            <div class="rating__el rating-el rating-el_active"></div>
                            <div class="rating__el rating-el rating-el_active"></div>
                            <div class="rating__el rating-el"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="btn-green tapes-else">
                <a href="#">Смотреть еще <img src="/img/tape/else_icon.svg" alt=""></a>
            </div>
        </div>
    </section>

            @endsection
            @section('scripts')
                <script type="text/javascript" src="{{asset('js/load-more.js')}}"></script>
@endsection

