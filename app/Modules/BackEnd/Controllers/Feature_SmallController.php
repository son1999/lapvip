<?php

namespace App\Modules\BackEnd\Controllers;

use Illuminate\Http\Request;

use App\Models\FeatureSmall as THIS;
class Feature_SmallController extends BackendController
{
    protected $timeStamp = 'created';

    //config controller, ez for copying and paste
    public function __construct(){
        parent::__construct(new THIS(),[
            [
                'image' => 'required|mimes:jpeg,jpg,png,gif',
                'link' => 'nullable|url',
            ]
        ]);

        \View::share('options', THIS::OPTIONS);
    }

    public function index(Request $request){
        $order = 'created DESC, id DESC';
        $cond = [['status','>',0]];
        if($request->title != ''){
            $cond[] = ['title','LIKE','%'.$request->title.'%'];
        }
        if($request->position != ''){
            $cond[] = ['positions', $request->position];
        }
        if($request->lang != ''){
            $cond[] = ['lang','=',$request->lang];
        }
        if(!empty($request->time_from)){
            $timeStamp = \Lib::getTimestampFromVNDate($request->time_from);
            array_push($cond, ['created', '>=', $timeStamp]);
        }
        if(!empty($request->time_to)){
            $timeStamp = \Lib::getTimestampFromVNDate($request->time_to, true);
            array_push($cond, ['created', '<=', $timeStamp]);
        }
        if(!empty($cond)) {
            $data = THIS::where($cond)->orderByRaw($order)->paginate($this->recperpage);
        }else{
            $data = THIS::orderByRaw($order)->paginate($this->recperpage);
        }
        return $this->returnView('index', [
            'data' => $data,
            'search_data' => $request
        ]);
    }

    public function buildValidate(Request $request){

        if($this->editMode){
            $this->ignoreValidate('image');
            if ($request->hasFile('image')) {
                $this->addValidate(['image' => 'mimes:jpeg,jpg,png,gif']);
            }
        }
    }

}
