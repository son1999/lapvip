<?php

namespace App\Modules\FrontEnd\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Menu;
use App\Models\OldCollection;
use App\Models\Page;
use App\Models\Product;
use App\Models\Warehouse;


class StaticPageController extends Controller
{   //
    public function __construct(){

    }

    public function index($link_seo){
        $page = Page::where('alias', $link_seo)->first();
        $warehouse = Warehouse::where('status', '>', 1)->orderBy('id', 'desc')->first();
        if($page && $page->status == 2){
            return view('FrontEnd::pages.page.index', [
                'site_title' => $page->title_seo,
                'data' => $page,
                'warehouse' => $warehouse,
            ]);
        }
        return abort('404');
    }
    public function oldCollection(){
        $lang = \Lib::getDefaultLang();
        $data = OldCollection::where('status', '>', 1)->where('lang', $lang)->first();
        $product = OldCollection::getProductPageOldCollection($data->id, 8);

        return view('FrontEnd::pages.oldCollection.index', [
            'site_title' => $data->title,
            'data' => $data,
            'product' => $product,
        ]);
    }
}
