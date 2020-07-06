<?php

namespace App\Modules\BackEnd\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Contact as THIS;
class ContactController extends BackendController
{
    //config controller, ez for copying and paste
    protected $timeStamp = 'created';
    protected $foods_perpage = 5;

    public function __construct()
    {
        parent::__construct(new THIS());
    }

    public function index(Request $request)
    {
        $order = 'created DESC, id DESC';
        $cond = [];

        if ($request->status != '') {
            $cond[] = ['status', $request->status];
        } else {
            $cond[] = ['status', '>', 0];
        }
        if ($request->phone != '') {
            $cond[] = ['phone', 'LIKE', '%' . $request->phone . '%'];
        }
        if ($request->email != '') {
            $cond[] = ['email', 'LIKE', '%' . $request->email . '%'];
        }
        if ($request->fullname != '') {
            $cond[] = ['fullname', 'LIKE', '%' . $request->full_name . '%'];
        }

        if (!empty($request->fromTime)) {
            array_push($cond, ['created', '>=', \Lib::getTimestampFromVNDate($request->fromTime)]);
        }

        $data = THIS::where($cond)->orderByRaw($order)->paginate($this->recperpage);
        return $this->returnView('index', [
            'data'           => $data,
            'search_data'    => $request,

        ]);
    }

    public function view($id)
    {
        $data = THIS::find($id)->first();

        $title = 'Xem thông tin khách hàng';
        return $this->returnView('view', [
            'site_title'       => $title,
            'data'             => $data,
        ], $title);
    }


}