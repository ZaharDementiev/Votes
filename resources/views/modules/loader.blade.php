<div class="wrapper-load" style="width: 100%; height: 99999px; z-index: 9999999999; position: absolute; background-color: #ffffff;
    @if(Route::current() && Route::current()->getName() == 'show')
        display:block;
    @else
        display:none;
    @endif
        ";>
    <div class="wrap-load"></div>
</div>