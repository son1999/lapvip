<?php

namespace App\Modules\Mobile\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class LanguageController extends Controller
{
    //
    public function __construct(){}

    public function change($lang){
        Cookie::queue(\Lib::getLanguageKey(), $lang, 60*24*365);
        \App::setLocale($lang);

        //Neu dang o trang news thi ra ngoai luon
        $back_url = redirect()->back()->getTargetUrl();
        if (str_contains($back_url, '/news/')) {
            return redirect()->route('home');
        }
        return redirect()->back();
    }

    public function getJson(){
        \Cache::forget('lang.js');
        $strings = \Cache::rememberForever('lang.js', function () {
            $lang = \App::getLocale();

            $files   = glob(resource_path('lang/' . $lang . '/*.php'));
            $strings = [];

            foreach ($files as $file) {
                $name           = basename($file, '.php');
                $strings[$name] = require $file;
            }

            return $strings;
        });

        header('Content-Type: text/javascript');
        echo('window.i18n = ' . json_encode($strings) . ';');
        exit();
    }
}
