<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\FilterCate;
use App\Models\GeoProvince;
use App\Models\Loans;
use App\Models\Feature;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('FrontEnd::pages.checkout.cart',function(){
            $province = GeoProvince::all()->sortBy('Name_VI');
            View::share('pro', $province);
        });

        \view()->composer('FrontEnd::layouts.header', function (){
            $sli = Feature::getSlideByPositions('vi','menu')->limit(1)->get();
            View::share('sli', $sli);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
