<?php

namespace App\Modules\Mobile\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//custom models



class AjaxController extends Controller
{
	public function __construct()
	{
		//
	}

	public function init(Request $request, $cmd){
		switch ($cmd) {
			case 'mobile':
				$data = $this->mobile($request);
				break;
			default:
				$data = $this->nothing();
		}
		return response()->json($data);
	}

	public function mobile(Request $request){
        return \Lib::ajaxRespond(true, 'success', ['mobile' => 'Hello world !!!']);
	}

	public function nothing(){
		return "Nothing...";
	}
}
