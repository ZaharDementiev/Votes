@extends('layouts/app')

@section('content')

    <section class="sec-publication">
    <div class="wrap-publication">
        <div class="wrap-publication-content">
            <div class="publication-content-form">
                <form method="post" action="{{route('edit-post', $post->id)}}" enctype="multipart/form-data">
                    @csrf
                    <div class="publication-content-form-el">
                        <h4>Название</h4>
                        <div class="inputs">
                            <div class="input-el">
                                <input class="inp_maxlength" minlength="2" maxlength="120" name="title" type="text" value="{{$post->title}}">
                                <div class="input-el__media">
                                    <p>Осталось - <span class="count_limit">120</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="publication-content-form-el">
                        <h4>Текст</h4>
                        <div class="textarea">
                            <div class="textarea-block maxlength">
                                <textarea name="description" class="textarea-block__textarea" minlength="5"
                                          maxlength="2000">{{$post->description}}</textarea>
                                <div class="textarea-block-media">
                                    <div class="textarea-block-media_el">
                                    </div>
                                </div>
                            </div>
                            <div class="input-el__media">
                                <p>Осталось - <span class="count_limit">2000</span></p>
                            </div>
                        </div>
                    </div>

{{--                    <div class="publication-content-form-el">--}}
{{--                        <h4>Тема</h4>--}}
{{--                        <div class="select">--}}
{{--                            <select required class="select_jq" name="tags[]" data-placeholder=" " multiple>--}}
{{--                                @foreach($tags as $tag)--}}
{{--                                    @foreach($post->tags as $selected)--}}
{{--                                        <option value="{{$tag->id}}">{{$tag->name}}</option>--}}
{{--                                    @endforeach--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <div class="publication-content-form-bottom" style="margin-bottom: 20px">
                        <div class="publication-content-form-btn btn-green">
                            <button type="submit">Опубликовать</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
