<?php
Route::get('/', [ 'as' => 'admin.checkAuthNow', 'uses' => 'App\Modules\BackEnd\Controllers\HomeController@checkAuth']);

Route::group(
    [
        'prefix' => 'admin',
        'module' => 'BackEnd',
        'namespace'=>'App\Modules\BackEnd\Controllers',
        'middleware' => ['web']
    ],
    function(){
        //login
        // Route::get('pagination/fetch_data', 'AjaxController@fetch_data');
//        Route::any('ajax/{cmd}', ['as' => 'admin.ajax', 'uses' => 'AjaxController@init']);

        Auth::routes();
        Route::get('logout', [ 'as' => 'logout', 'uses' => '\App\Modules\BackEnd\Controllers\Auth\LoginController@logout']);
        Route::get('register/success', [ 'as' => 'register.success', 'uses' => '\App\Modules\BackEnd\Controllers\Auth\RegisterController@success']);
        Route::get('/excel', [ 'as' => 'export.orderInstallment', 'uses' => '\App\Modules\BackEnd\Controllers\OrderInstallmentController@export']);
//        Route::get('/test', function (){
//            return view('BackEnd::pages.export.OrderInstallment');
//        });

        /**  page - admin **/
        Route::group(['middleware' => ['auth']], function (){
            //home page
            Route::get('home', [ 'as' => 'admin.home', 'uses' => 'HomeController@index']);



            $basicGroup = config('permission');

            foreach ($basicGroup['backend'] as $key => $value){
                $prefixController = $value['controller'];
                Route::any('ajax/'.$key.'/{cmd}', ['as' => 'admin.'.$key.'ajax', 'uses' => $prefixController.'Controller@ajax']);

                $perms = isset($value['perm']) ? $value['perm'] : \App\Models\Role::$defRoles;
                if(count($perms) == 1 && isset($value['form']) && $value['form'] == 1){
                    foreach ($perms as $perm => $perm_title) {
                        Route::get($key, ['as' => 'admin.' . $key, 'uses' => $prefixController . 'Controller@index'])->middleware('can:' . $key . '-'.$perm.',App\Models\Role::' . $key . '-'.$perm);
                        Route::post($key, ['as' => 'admin.' . $key . '.post', 'uses' => $prefixController . 'Controller@submit'])->middleware('can:' . $key . '-'.$perm.',App\Models\Role::' . $key . '-'.$perm);
                        break;
                    }
                }else{
                    \Lib::set('route', [$key, $prefixController, $perms]);
                    Route::group(['prefix' => $key, 'middleware' => 'can:'.$key.'-view,App\Models\Role::'.$key.'-view'], function() {
                        $key = \Lib::get('route')[0];
                        $prefixController = \Lib::get('route')[1];
                        $perms = \Lib::get('route')[2];
                        foreach ($perms as $perm => $perm_title) {
                            switch ($perm){
                                case 'view':
                                    Route::any('/', ['as' => 'admin.'.$key, 'uses' => $prefixController.'Controller@index']);
                                    break;
                                case 'add':
                                    Route::get('add', [ 'as' => 'admin.'.$key.'.add', 'uses' => $prefixController.'Controller@showAddForm'])
                                        ->middleware('can:'.$key.'-add,App\Models\Role::'.$key.'-add');
                                    Route::post('add', [ 'as' => 'admin.'.$key.'.add.post', 'uses' => $prefixController.'Controller@save'])
                                        ->middleware('can:'.$key.'-add,App\Models\Role::'.$key.'-add');
                                    break;
                                case 'edit':
                                    Route::get('edit/{id}', [ 'as' => 'admin.'.$key.'.edit', 'uses' => $prefixController.'Controller@showEditForm'])
                                        ->middleware('can:'.$key.'-edit,App\Models\Role::'.$key.'-edit');
                                    Route::post('edit/{id}', [ 'as' => 'admin.'.$key.'.edit.post', 'uses' => $prefixController.'Controller@save'])
                                        ->middleware('can:'.$key.'-edit,App\Models\Role::'.$key.'-edit');
                                    break;
                                case 'delete':
                                    Route::get('delete/{id}', [ 'as' => 'admin.'.$key.'.delete', 'uses' => $prefixController.'Controller@delete'])
                                        ->middleware('can:'.$key.'-delete,App\Models\Role::'.$key.'-delete');
                                    break;
                                case 'log' :
                                    Route::any('log/{id}', [ 'as' => 'admin.'.$key.'.log', 'uses' => $prefixController.'Controller@log'])->middleware('can:'.$key.'-view,App\Models\Role::'.$key.'-view');
                                    break;

                            }
                        }
                        //mo rong cac form dac biet
                        switch ($key){
                            case 'user':
                                Route::any('log/{id}', ['as' => 'admin.'.$key.'.log', 'uses' => $prefixController.'Controller@log']);
                                Route::get('profile', ['as' => 'admin.'.$key.'.profile', 'uses' => $prefixController.'Controller@showProfileForm']);
                                Route::post('profile', ['as' => 'admin.'.$key.'.profile.post', 'uses' => $prefixController.'Controller@updateProfile']);
                            case 'contact':
                                Route::any('view/{id}', ['as' => 'admin.'.$key.'.view', 'uses' => $prefixController.'Controller@view']) ->middleware('can:'.$key.'-view,App\Models\Role::'.$key.'-view');
                                break;
                            case 'question':
                                Route::any('view/{id}', ['as' => 'admin.'.$key.'.view', 'uses' => $prefixController.'Controller@view']) ->middleware('can:'.$key.'-view,App\Models\Role::'.$key.'-view');
                                break;
                            case 'answer_question':
                                Route::any('view/{id}', ['as' => 'admin.'.$key.'.view', 'uses' => $prefixController.'Controller@view']) ->middleware('can:'.$key.'-view,App\Models\Role::'.$key.'-view');
                                break;
                            case 'order':
                                Route::any('log/{id}', ['as' => 'admin.'.$key.'.log', 'uses' => $prefixController.'Controller@log']);
                                Route::any('view/{id}', ['as' => 'admin.'.$key.'.view', 'uses' => $prefixController.'Controller@view']) ->middleware('can:'.$key.'-view,App\Models\Role::'.$key.'-view');
                                break;
                            case 'order_installment':
                                Route::any('view/{id}', ['as' => 'admin.'.$key.'.view', 'uses' => $prefixController.'Controller@view']) ->middleware('can:'.$key.'-view,App\Models\Role::'.$key.'-view');
                                break;
                            case 'coupon':
                                Route::post('coupon/import', ['as' => 'admin.'.$key.'.import.post', 'uses' => $prefixController.'Controller@import']);
//                                    ->middleware('can:'.$key.'-coupon_sys,App\Models\Role::'.$key.'-add');
                                Route::post('coupon/save', ['as' => 'admin.'.$key.'.save.post', 'uses' => $prefixController.'Controller@saveAjax']);
                                break;
                            case 'spin':
                                Route::post('coupon/import', ['as' => 'admin.'.$key.'.import.post', 'uses' => $prefixController.'Controller@import']);
//                                    ->middleware('can:'.$key.'-coupon_sys,App\Models\Role::'.$key.'-add');
                                Route::post('coupon/save', ['as' => 'admin.'.$key.'.save.post', 'uses' => $prefixController.'Controller@saveAjax']);
                                Route::post('coupon/index', ['as' => 'admin.'.$key.'.show', 'uses' => $prefixController.'Controller@index']);
                                break;
                        }
                    });
                }
            }
        });
    }
);