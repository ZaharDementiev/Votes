@extends('layouts/app')

@section('content')
    @if($errors)
        @foreach ($errors->all() as $error)
            <div><h2 style="color: red">{{ $error }}</h2></div>
        @endforeach
    @endif



            <text-publication :tags="{{$tags}}"></text-publication>


{{--    </div>--}}
{{--    </div>--}}
{{--    --}}{{--                    <div class="sample" style="display: none">--}}
{{--    --}}{{--                        <div class="form-group">--}}
{{--    --}}{{--                            <label>Добавить вариант ответа</label>--}}
{{--    --}}{{--                            <input type="button"--}}
{{--    --}}{{--                                   class="btn btn-primary"--}}
{{--    --}}{{--                                   value="+"--}}
{{--    --}}{{--                                   @click="addGuest"--}}
{{--    --}}{{--                            >--}}
{{--    --}}{{--                        </div>--}}
{{--    --}}{{--                        <div>--}}
{{--    --}}{{--                            <div class="form-group" v-for="(guest, index) in guests">--}}
{{--    --}}{{--                                <label @dblclick="deleteGuest(index)">--}}
{{--    --}}{{--                                    Вариант @{{ index + 1 }}--}}
{{--    --}}{{--                                </label>--}}
{{--    --}}{{--                                <input type="text" name="options[]" class="form-control" v-model="guests[index]">--}}
{{--    --}}{{--                            </div>--}}
{{--    --}}{{--                        </div>--}}
{{--    --}}{{--                    </div>--}}
{{--    </form>--}}
{{--    </div>--}}

@endsection
@section('scripts')
    {{--    <script src="//cdnjs.cloudflare.com/ajax/libs/masonry/4.1.1/masonry.pkgd.min.js"></script>--}}
    {{--    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/4.1.1/imagesloaded.pkgd.min.js"></script>--}}
    {{--    <script src="//cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>--}}
    {{--<script>--}}
    {{--    $( document ).ready(function() {--}}

    {{--        new Vue({--}}
    {{--            el: '.sample',--}}
    {{--            data: {--}}
    {{--                guests: []--}}
    {{--            },--}}
    {{--            methods: {--}}
    {{--                addGuest() {--}}
    {{--                    this.guests.push('');--}}
    {{--                },--}}
    {{--                deleteGuest(index) {--}}
    {{--                    this.guests.splice(index, 1);--}}
    {{--                }--}}
    {{--            }--}}
    {{--        })--}}
    {{--    });--}}
    {{--</script>--}}



@endsection
