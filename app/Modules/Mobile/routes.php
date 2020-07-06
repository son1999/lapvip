<?php
Route::group(
    [
        'module' => 'Mobile',
        'namespace'=>'App\Modules\Mobile\Controllers',
        'middleware' => ['web']
    ],
    function(){
	    //for ajax
	    Route::any('ajax/{cmd}', [ 'as' => 'ajax', 'uses' => 'AjaxController@init']);

	    //home
	    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
    }
);

