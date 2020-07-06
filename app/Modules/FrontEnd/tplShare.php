<?php

\View::share('def', \Lib::tplShareGlobal());
\View::share('isHome', false);
\View::share('defLang', \Lib::getDefaultLang());
\View::share('menu', \Menu::getMenuWithFilter(3));
\View::share('menu_footer', \Menu::getMenu(4));
\View::share('category', \App\Models\Category::getCat(1));
//\View::share('news', \App\Models\Category::getCat(2));
\View::share('installment', \App\Models\Category::getCat(3));
\View::share('seoDefault', \App\Models\ConfigSite::getSeo());
\View::share('fill_cate_menu', \App\Models\FilterCate::getByPrdCateMenu());
\View::share('pro', \App\Models\GeoProvince::all()->sortBy('Name_VI'));

//\View::share('cart_total', \App\Libs\Cart::getInstance()->total());