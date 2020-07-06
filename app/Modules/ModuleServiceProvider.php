<?php

namespace App\Modules;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider{

    public function boot(){

        //process https on product
        if(env('APP_HTTPS')) {
            \URL::forceScheme('https');
        }

        //get folder_name from config/module
        $folder = \Lib::module_config('folder_name');

        //Load file routes.php tuong ung cua tung module
        if(file_exists(__DIR__ . '/' . $folder . '/routes.php')){
            include __DIR__ . '/' . $folder . '/routes.php';
        }

        //Load file template share ung voi tung module
        if(file_exists(__DIR__ . '/' . $folder . '/tplShare.php')){
            include __DIR__ . '/' . $folder . '/tplShare.php';
        }

        //Load cac file template tuong ung trong tung module
        if(is_dir(__DIR__ . '/' . $folder . '/Views')){
            $this->loadViewsFrom(__DIR__ . '/' . $folder . '/Views', $folder);
        }
    }

    public function register(){

    }
}