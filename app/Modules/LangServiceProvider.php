<?php

namespace App\Modules;

use Illuminate\Support\ServiceProvider;

class LangServiceProvider extends ServiceProvider{

    public function boot(){
        //set language
        \Lib::getDefaultLang();
        \App::setLocale(\Lib::getDefaultLang());
    }

    public function register(){

    }
}