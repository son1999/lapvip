<?php

namespace App\Modules\Mobile\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use App\Models\Blog;
use App\Models\Cheap;
use App\Models\Tour;
use Illuminate\Http\Request;


class HomeController extends Controller
{   //
	public function __construct(){

	}

	public function index(){
		view()->share('isHome', true);

		return view('Mobile::pages.home.index', [
			'site_title' => __('site.trangchu')
		]);
	}
}
