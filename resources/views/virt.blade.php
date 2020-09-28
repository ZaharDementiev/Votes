@extends('layouts/app')
@section('content')
    @php $tags = \App\Tag::all() @endphp
    <section class="sec-topic">
        <women-list :list="{{$women}}" :tags="{{$tags}}" @auth :user="{{auth()->user()}}" @else :user="0" @endauth></women-list>
    </section>

@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset('js/load-more.js')}}"></script>
@endsection

