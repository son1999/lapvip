<?php

namespace App\Modules\BackEnd\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfigSite as THIS;

class ConfigController extends BackendController
{
    public function __construct(){
        parent::__construct(new THIS());
        $this->bladeAdd = 'add';
        $this->registerAjax('route', 'ajaxUpdateRoute', 'change');
    }

    public function index(Request $request){
        return $this->returnView('index', [
            'site_title' => $this->title,
            'data' => $this->loadInfo()
        ]);
    }

    public function submit(Request $request){
        $valid = [
            [
                'site_name' => 'required|max:250',
                'email' => 'required|email',
                'version' => 'required',
            ]
        ];
        $this->validate($request, $valid[0], isset($valid[1]) ? $valid[1] : []);
        $default = $this->loadInfo();
        $default['image'] = $this->uploadImage($request, $request->site_name, 'image');
        foreach ($request->all() as $k => $v){
            if($k != '_token' && $k != 'image') {

                if (!empty($v)) {
                    $default[$k] = $v;
                } else {
                    $default[$k] = $v==0?$v:'';
                }
            }
        }
        THIS::setConfig($this->key, json_encode($default));
        return redirect()->route('admin.'.$this->key)->with('status', 'Đã cập nhật thành công');
    }

    protected function loadInfo(){
        $data = THIS::getConfig($this->key, '');
        return !empty($data) ? json_decode($data, true) : null;
    }

    protected function ajaxUpdateRoute(){
        $routes = \Lib::saveRoutes(false);
        return \Lib::ajaxRespond(true, 'ok', $routes);
    }
}
