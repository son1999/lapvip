<?php

namespace App\Modules\BackEnd\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CustomerCardPay as THIS;
class CustomerCardPayController extends BackendController
{
    public function __construct(){
        parent::__construct(new THIS());
    }

    public function index(Request $request){
        $data = THIS::where('id', 1)->first();
//        dd(THIS::class);
        return $this->returnView('index', [
            'data' => $data,
        ]);
    }
}
