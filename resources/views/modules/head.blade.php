 <meta charset="utf-8">
<link rel="icon" href="/img/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">
<!-- viewport -->
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />

@if(Route::current() && Route::current()->getName() == 'live')
    <title>Lady Secrets - лента женских секретов, советов, вопросов и ответов | Онлайн общение, анонимные публикации</title>
    <meta name="description" content="Общение женщин: ответы на вопросы, истории из жизни, советы, обсуждения и много другое на Lady Secrets. Сайт для женщин, публикация анонимных историй и обсуждение проблем.">
@elseif(Route::current() && Route::current()->getName() == 'discussed')
    <title>Обсуждаемые публикации женщин на Lady Secrets</title>
    <meta name="description" content="Самые обсуждаемые женские темы на Lady Secrets.">
@elseif(Route::current() && Route::current()->getName() == 'popular')
    <title>Популярные публикации женщин на Lady Secrets</title>
    <meta name="description" content="Самые просматриваемые и популярные публикации на Lady Secrets.">
@elseif(Route::current() && Route::current()->getName() == 'tags')
    <title>Тематические категории публикаций на Lady Secrets</title>
    <meta name="description" content="Все темы обсуждений на сайте Lady Secrets.">
@endif
<!-- Scripts -->
<!--<script src="{{asset('js/app.js')}}"></script>-->
<script src= "{{asset('js/jquery.js')}}"></script>
<script src= "{{asset('js/select2.min.js')}}"></script>
<!--<script src= "{{asset('js/follow.js')}}"></script>-->

<script src= "{{asset('js/emojionearea.min.js')}}"></script>

<script src= "{{asset('js/angular.min.js')}}"></script>
<script src= "{{asset('js/bootstrap.min.js')}}"></script>
<script src= "{{asset('js/moment.min.js')}}"></script>
<!--<script type="text/javascript" src="{{asset('js/load-more.js')}}"></script>-->

<!--<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>-->

<link rel="stylesheet" href="{{asset('css/fonts.css')}}">

<link rel="stylesheet" href="{{asset('css/slick.css')}}">
<link rel="stylesheet" href="{{asset('css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('css/emojionearea.min.css')}}">
<link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
<link rel="stylesheet" href="{{asset('css/main.css')}}">
<!--<script src="{{ asset('js/emojionearea.min.js') }}"></script>-->

<link href="{{asset('css/site.css')}}" rel="stylesheet">

<link rel="stylesheet" href="{{asset('css/jquery.fancybox.min.css')}}">