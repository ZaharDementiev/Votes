<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        date_default_timezone_set('Europe/Moscow');
//        View::share('shorten_number', function ($n) {
//            if ($n > 0 && $n < 1000) {
//                // 1 - 999
//                $n_format = floor($n);
//                $suffix = '';
//            } else if ($n >= 1000 && $n < 1000000) {
//                // 1k-999k
//                $n_format = floor($n / 1000);
//                $suffix = 'K+';
//            } else if ($n >= 1000000 && $n < 1000000000) {
//                // 1m-999m
//                $n_format = floor($n / 1000000);
//                $suffix = 'M+';
//            } else if ($n >= 1000000000 && $n < 1000000000000) {
//                // 1b-999b
//                $n_format = floor($n / 1000000000);
//                $suffix = 'B+';
//            } else if ($n >= 1000000000000) {
//                // 1t+
//                $n_format = floor($n / 1000000000000);
//                $suffix = 'T+';
//            }
//
//            return !empty($n_format . $suffix) ? $n_format . $suffix : 0;
//        });
    }
}
