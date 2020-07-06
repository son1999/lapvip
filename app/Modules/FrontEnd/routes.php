<?php
Route::group(
    [
        'module' => 'FrontEnd',
        'namespace'=>'App\Modules\FrontEnd\Controllers',
        'middleware' => ['web','XSS']
    ],
    function(){

        //for ajax
        Route::any('ajax/{cmd}', [ 'as' => 'ajax', 'uses' => 'AjaxController@init']);
        Route::get('getDistrict', 'HomeController@getDistrictList');
        Route::get('getWarehouse', 'HomeController@getWarehouse');
        //home
        Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);

        Route::get('/{safe_title}-ptag{id}', ['as' => 'product.tags', 'uses' => 'ProductController@tags'])
            ->where([
                'safe_title' => '[a-zA-Z0-9_\-]+',
                'id' => '[0-9]+'
            ]);

	    Route::get('/category/detail', function (){
		    return view('FrontEnd::pages.category.detail', [
			    'site_title' => 'Chi tiết danh mục',
			    'menu' => \Menu::getMenu(3)
		    ]);
	    });
        //danh muc
        Route::get('/list-{safe_title}', ['as' => 'product.list', 'uses' => 'ProductController@list'])
            ->where(['safe_title' => '[a-zA-Z0-9_\-]+']);


        //Static page
        Route::group(['prefix' => 'page'], function (){
            Route::get('/{link_seo}', ['as' => 'trangtinh', 'uses' => 'StaticPageController@index'])
                ->where(['link_seo' => '[a-zA-Z0-9_\-]+', 'fid' => '[0-9]+']);
        });
        //News
        Route::group(['prefix' => 'tin-tuc'], function() {
            Route::get('/{slug_title}', ['as' => 'news.list', 'uses' => 'NewsController@index']);
            Route::get('/{cat_title}/{alias}', ['as' => 'news.detail', 'uses' => 'NewsController@detail']);
        });

        //old-collection

        Route::get('/thu-cu-doi-moi', ['as' => 'old_collection', 'uses' => 'StaticPageController@oldCollection']);



        //products

        Route::group([ 'as' => 'product.'], function() {
            Route::get('/{alias}-p{parent_id}', ['as' => 'list', 'uses' => 'ProductController@index'])
                ->where([
                    'alias' => '[a-zA-Z0-9_\-]+',
                    'parent_id' => '[0-9]+',
                    'child_id' => '[0-9]+'
                ]);
            Route::post('/load-more', ['as'=> 'loadMoreAjax', 'uses' => 'ProductController@loadMoreAjax'])
                ->where([
                    'alias' => '[a-zA-Z0-9_\-]+',
                    'parent_id' => '[0-9]+',
                    'child_id' => '[0-9]+'
                ]);
            Route::get('/sale', ['as' => 'sale', 'uses' => 'ProductController@sale']);
            Route::get('/{alias}', ['as' => 'detail', 'uses' => 'ProductController@detail'])
                ->where([
                    'alias' => '[a-zA-Z0-9_\-]+'
                ]);
            Route::get('/phu-kien/{alias}', ['as' => 'detail.accessory', 'uses' => 'ProductController@detail_accessory'])
                ->where([
                    'alias' => '[a-zA-Z0-9_\-]+'
                ]);
            Route::get('/promotion', ['as' => 'product.promotion', 'uses' => 'ProductController@promotion']);

            // comment
            Route::post('/post_commnet', ['as' => 'comment', 'uses' => 'ProductController@_saveComment']);
            Route::post('/post_question', ['as' => 'question', 'uses' => 'ProductController@_saveQuestion']);
            Route::get('/filter/{alias_filter}', ['as' => 'filter', 'uses' => 'ProductController@filter']);

        });

        //compare
        Route::group(['prefix' => 'so-sanh-san-pham'], function (){
            Route::get('/{pro_parent}-compare-{pro_child}', ['as' => 'com.product.compare', 'uses' => 'ProductController@compare'])->where([
                'pro_parent' => '[a-zA-Z0-9_\-]+',
                'pro_child' => '[a-zA-Z0-9_\-]+',
            ]);
        });


        //Installment
        Route::group([ 'as' => 'installment.'], function (){
            Route::get('/tra-gop/{alias}', ['as' => 'scenarios', 'uses' => 'ProductController@_installment']);

        });
        Route::group(['as' => 'save.'], function (){
            Route::get('/save-info/{alias}', ['as' => 'saveInfo', 'uses' => 'ProductController@_saveInstallment']);
            Route::post('/save-info/{alias}', ['as' => 'saveSuccess', 'uses' => 'ProductController@_saveSuccessInstallment']);

        });

        Route::post('/post-question-installment', ['as' => 'installment.question', 'uses'=>'ProductController@installment_question']);
//        Route::get('/{alias}-c{cid}', ['as' => 'laptop', 'uses' => 'ProductController@index']);
        //contact

        Route::get('/contact', ['as' => 'contact', 'uses' => 'ContactController@index']);
        Route::post('/contact', ['as' => 'contact.post', 'uses' => 'ContactController@sendcontact']);

        //danh mục sản phẩm
        Route::get('{safe_title}-c{id}', ['as' => 'category.detail', 'uses' => 'CategoryController@detail'])
        ->where([
            'safe_title' => '[a-zA-Z0-9_\-]+',
            'id' => '[0-9]+'
        ]);
//        Route::group(['prefix' => 'product'], function() {
//            Route::get('/', ['as' => 'product.list', 'uses' => 'CategoryController@index']);
//            Route::get('/promotion', ['as' => 'product.promotion', 'uses' => 'ProductController@promotion']);
//        });


//        Route::get('/search', ['as' => 'product.search', 'uses' => 'ProductController@search']);
        //search
        Route::group(['prefix' => 'search'], function (){
            Route::get('/search-key', ['as'=>'product.search.key', 'uses' => 'ProductController@searchByKey']);
            Route::get('/search-lapvip', ['as'=>'product.searchForm.key', 'uses' => 'ProductController@searchKey']);
        });
        //customer
        Route::group(['prefix' => 'profile'], function() {
            Route::get('/', ['as' => 'profile', 'uses' => 'ProfileController@index']);
            Route::post('/', ['as' => 'profile.post', 'uses' => 'ProfileController@update']);

            Route::get('/orders', ['as' => 'orders', 'uses' => 'ProfileController@orders']);
            Route::get('/orders/{id}', ['as' => 'orders.detail', 'uses' => 'ProfileController@orderDetail']);

            Route::get('/viewed', ['as' => 'viewed', 'uses' => 'ProfileController@viewed']);
        });

        Route::group(['prefix' => 'checkout', 'as' => 'cart.checkout.'], function() {
            Route::get('/cart', ['as' => 'cart', 'uses' => 'CheckoutController@cart']);
            Route::post('saveinfo', ['as' => 'saveinfo', 'uses' => 'CheckoutController@saveInfo']);
            Route::get('payment_type', ['as' => 'cart.checkout.choose_type', 'uses' => 'CheckoutController@chooseTypePayment']);
            Route::any('confirm', ['as' => 'cart.checkout.confirm', 'uses' => 'CheckoutController@confirmInfo']);
            Route::post('process', ['as' => 'cart.checkout.process', 'uses' => 'CheckoutController@process']);
            Route::any('done', ['as' => 'cart.checkout.done', 'uses' => 'CheckoutController@done']);
            Route::any('err', ['as' => 'cart.checkout.err', 'uses' => 'CheckoutController@error']);
            Route::get('destroy', ['as' => 'cart.destroy', 'uses' => 'CheckoutController@destroy']);

            Route::post('/cart', ['as' => 'cart.post', 'uses' => 'CheckoutController@cart_post']);
            Route::get('/infor', ['as' => 'cart_infor', 'uses' => 'CheckoutController@cart_infor']);
            Route::get('/complete', ['as' => 'cart_complete', 'uses' => 'CheckoutController@cart_complete']);
            Route::get('/payment_success', ['as' => 'cart.payment.success', 'uses' => 'CheckoutController@success_online']);
            Route::post('/complete', ['as' => 'cart_complete.post', 'uses' => 'CheckoutController@cart_complete_post']);
        });

        Route::get('email/{tpl}', ['as' => 'email', 'uses' => 'EmailController@showEmailTemplate']);

        Route::get('register/success', ['as' => 'register.success', 'uses' => 'Auth\AuthController@showRegSuccess']);
        Route::get('register/verify', ['as' => 'register.verify', 'uses' => 'Auth\AuthController@regVerify']);

        Route::get('login', ['as' => 'login', 'uses' => 'Auth\AuthController@showLoginForm']);
        Route::post('login', ['as' => 'login.post', 'uses' => 'Auth\AuthController@login']);
        Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@logout']);

        Route::group(['prefix' => 'password'], function() {
            Route::get('reset', ['as' => 'password', 'uses' => 'Auth\AuthController@showPasswordRequestForm']);
            Route::post('email', ['as' => 'password.post', 'uses' =>'Auth\AuthController@sendResetLinkEmail']);
            Route::get('reset/{token}', ['as' => 'password.reset', 'uses' =>'Auth\AuthController@showPasswordResetForm']);
            Route::post('reset', ['as' => 'password.reset.post', 'uses' =>'Auth\AuthController@reset']);
        });

        Route::group(['prefix' => 'auth'], function() {
            Route::get('facebook/redirect', ['as' => 'facebook.login', 'uses' => 'Auth\SocialAuthFacebookController@redirect']);
            Route::get('facebook/callback', ['as' => 'facebook.callback', 'uses' => 'Auth\SocialAuthFacebookController@callback']);
        });

        Route::group(['prefix' => 'auth'], function() {
            Route::get('google/redirect', ['as' => 'google.login', 'uses' => 'Auth\SocialAuthGoogleController@redirect']);
            Route::get('google/callback', ['as' => 'google.callback', 'uses' => 'Auth\SocialAuthGoogleController@callback']);
        });

        //set language
        Route::get('language/{lang}', ['as' => 'language', 'uses' => 'LanguageController@change']);
        Route::get('js/lang.js', ['as' => 'assets.lang', 'uses' => 'LanguageController@getJson']);

        //error
        Route::get('404', [ 'as' => 'public.404', 'uses' => 'ErrorController@page404']);
        Route::get('500', [ 'as' => 'public.500', 'uses' => 'ErrorController@page500']);


    }
);

