@extends('layouts/app')
@section('content')
    @php $tags = \App\Tag::all() @endphp
    <section class="sec-topic">
        <div class="wrap-topic">
            <div class="settings-content-el">
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
                <div class="settings-content-el-body">
                    <form class="form-settigns-el" action="{{route('virt-search')}}" method="post">
                        @csrf
                        <label class="wrap-radio__el radio-el form-settigns-el__el">
                            <span class="radio-el__text">Online</span>
                            <span class="radio-el__inp radio">
                          <input type="checkbox" name="online" class="radio__inp">
                          <span class="radio__dec">
                            <span class="radio__dec-circle"></span>
                          </span>
                        </span>
                        </label>
                        <div class="form-settigns-el__el">
                            <div class="wrap-range">
                                <div class="form-settigns-el__title title title_small">Возраст</div>
                                <div class="rangeSlider wrap-range__range">
                                    <input type="text" class="rangeSlider__inp js-range-slider" name="my_range" value="">
                                </div>
                            </div>
                        </div>
                        <div class="select form-settigns-el__el">
                            <div class="form-settigns-el__title title title_small">Теги</div>
                            <select class="select_jq"
                                    data-placeholder="выберите не более 4-х тегов, начните вводить текст" multiple>
                                @foreach($tags as $tag)
                                    <option value="{{$tag->id}}">{{$tag->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        @if(!auth()->user()->vip)
                            <div class="vip-block">
                                <div class="vip-block__icon"></div>
                                <div class="vip-block__text">Доступно для VIP</div>
                            </div>
                        @else
                            <div class="btn-green">
                                <button type="submit">Сохранить</button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
            <div class="articles-users">
                @foreach($women as $woman)
                    <div class="articles-users__el article-user article-user_vip">
                        <div class="article-user__top">
                            <a href="{{route('profile', $woman->name)}}" class="article-user__img userpic">
                                <img src="{{$woman->avatar}}" alt="" class="userpic__img">
                            </a>
                        </div>
                        <div class="article-user__body">
                            <a href="{{route('profile', $woman->name)}}" class="article-user__name">{{$woman->name}}</a>
                            <div class="article-user__info">Оценка: <span class="article-user__info_bold">4,5</span>
                            </div>
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

