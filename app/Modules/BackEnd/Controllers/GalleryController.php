<?php

namespace App\Modules\BackEnd\Controllers;

use App\Models\Gallery;
use App\Models\GalleryCat;
use Illuminate\Http\Request;

use App\Models\Gallery as THIS;

class GalleryController extends BackendController
{
    //config controller, ez for copying and paste
    protected $timeStamp = 'created';

    public function __construct(){
        parent::__construct(new THIS());
        $this->bladeAdd = 'add';
        $this->registerAjax('cat-add', 'ajaxCatAdd', 'add');
        $this->registerAjax('cat-edit', 'ajaxCatAdd', 'edit');
        $this->registerAjax('cat-refresh', 'ajaxCatRefresh', 'edit');
        $this->registerAjax('cat-remove', 'ajaxCatRemove', 'delete');
        $this->registerAjax('load', 'ajaxImageLoad', 'view');
        $this->registerAjax('cover', 'ajaxImageCover', 'edit');
        $this->registerAjax('edit', 'ajaxImageEdit', 'edit');
        $this->registerAjax('remove', 'ajaxItemDel', 'delete');
        $this->registerAjax('upload', 'ajaxItemUploadMulti', 'add');
        $this->registerAjax('change-pos', 'ajaxItemChangePos', 'edit');
    }

    public function index(Request $request){
        $lang = config('app.locales');
        return $this->returnView('gallery', [
            'cat' => GalleryCat::getCategories(true),
            'search_data' => $request,
            'lang' => json_encode($lang),
        ]);
    }

    protected function ajaxItemChangePos(Request $request){
        if($request->id > 0 && $request->next > 0 && $request->type != ''){
            $next = Gallery::find($request->next);
            $cur  = Gallery::find($request->id);
            if($next && $cur){
                $cur->sort = $request->type == 'left' ? ($next->sort + 1) : ($next->sort - 1);
                $cur->save();
                return \Lib::ajaxRespond(true, 'ok');
            }
        }
        return \Lib::ajaxRespond(false, 'Dữ liệu không chính xác');
    }

    protected function ajaxImageLoad(Request $request){
        return \Lib::ajaxRespond(true, 'ok', ['images' => Gallery::getImageGallery($request->id)]);
    }

    protected function ajaxImageCover(Request $request){
        if($request->id > 0 && $request->cat_id > 0){
            $data = Gallery::find($request->id);
            if($data && $data->cat_id == $request->cat_id){
                Gallery::where('cat_id', $request->cat_id)->where('is_cover', 1)->where('lang', $data->lang)->update(['is_cover' => 0]);
                $data->is_cover = 1;
                $data->save();
                return \Lib::ajaxRespond(true, 'ok', Gallery::getImageGallery($request->cat_id));
            }
        }
        return \Lib::ajaxRespond(false, 'Dữ liệu không chính xác');
    }

    protected function ajaxImageEdit(Request $request){
        if($request->id > 0){
            $data = Gallery::find($request->id);
            if($data){
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    if ($image->isValid()) {
                        $title = basename($image->getClientOriginalName(), '.' . $image->getClientOriginalExtension());
                        $data->image = $this->uploadImage($request, $title, 'image');
                        $data->size = $image->getClientSize();
                        $data->type = $image->getClientMimeType();
                    }
                }
                $curCat = $data->cat_id;
                if($curCat != $request->cat_id){
                    $data->is_cover = 0;
                }
                $data->title = $request->title;
                $data->sort = $request->sort;
                $data->cat_id = $request->cat_id;
                $data->lang = $request->lang;
                $data->save();

                if($curCat != $request->cat_id){
                    GalleryCat::updateQuantity($curCat);
                    GalleryCat::updateQuantity($request->cat_id);
                }
                return \Lib::ajaxRespond(true, 'ok', ['images' => Gallery::getImageGallery($curCat), 'category' => GalleryCat::getCategories()]);
            }
        }
        return \Lib::ajaxRespond(false, 'Dữ liệu không chính xác');
    }

    protected function ajaxItemDel(Request $request){
        if($request->id > 0){
            $data = Gallery::find($request->id);
            if($data){
                //remove image
                $data->delete();
                //update quantity
                GalleryCat::updateQuantity($request->cat_id);
                return \Lib::ajaxRespond(true, 'ok', ['images' => Gallery::getImageGallery($request->cat_id), 'category' => GalleryCat::getCategories()]);
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }

    protected function ajaxItemUploadMulti(Request $request){
        if ($request->hasFile('Filedata')) {
            $image = $request->file('Filedata');
            if ($image->isValid()) {
                $title = basename($image->getClientOriginalName(), '.'.$image->getClientOriginalExtension());
                $fname = $this->uploadImage($request, $title, 'Filedata');
                if(!empty($fname)){
                    $imgGallery = new THIS();
                    $imgGallery->cat_id = $request->cat_id;
                    $imgGallery->title = $title;
                    $imgGallery->image = $fname;
                    $imgGallery->size = $image->getClientSize();
                    $imgGallery->type = $image->getClientMimeType();
                    $imgGallery->created = time();
                    $imgGallery->changed = time();
                    $imgGallery->uid = \Auth::id();
                    $imgGallery->uname = \Auth::user()->user_name;
                    $imgGallery->lang = $request->lang;
                    $imgGallery->sort = Gallery::getSortInsert($request->lang);
                    $imgGallery->save();
                    GalleryCat::updateQuantity($request->cat_id);
                    return \Lib::ajaxRespond(true, 'ok', ['images' => Gallery::getImageGallery($request->cat_id), 'category' => GalleryCat::getCategories()]);
                }
                return \Lib::ajaxRespond(false, 'Upload ảnh thất bại!');
            }
            return \Lib::ajaxRespond(false, 'File không hợp lệ!');
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy ảnh!');
    }

    protected function ajaxCatRefresh(Request $request){
        if ($request->id > 0) {
            $data = GalleryCat::find($request->id);
            if($data){
                //update so luong anh
                GalleryCat::updateQuantity($request->id);

                //del item
                return \Lib::ajaxRespond(true, 'success', GalleryCat::getCategories());
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }

    protected function ajaxCatRemove(Request $request){
        if ($request->id > 0) {
            $data = GalleryCat::find($request->id);
            if($data){
                //if total > 0, let move to default
                if($data->total > 0) {
                    //move item to default folder
                    Gallery::where('cat_id', $data->id)->update(['cat_id' => 1, 'is_cover' => 0]);

                    //update so luog danh muc mac dinh
                    GalleryCat::updateQuantity(1);
                }
                //del item
                $data->delete();
                return \Lib::ajaxRespond(true, 'success', ['images' => Gallery::getImageGallery(1), 'category' => GalleryCat::getCategories()]);
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }

    protected function ajaxCatAdd(Request $request){
        if(!empty($request->title)) {
            if ($request->id > 0) {
                $data = GalleryCat::find($request->id);
                if (empty($data)) {
                    return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
                }
            } else {
                $data = new GalleryCat();
                $data->created = time();
            }
            $data->title = $request->title;
            $data->safe_title = str_slug($request->title);
            $data->description = $request->des;
            $data->uid = \Auth::id();
            $data->uname = \Auth::user()->user_name;
            $data->save();
            //\MyLog::do()->add('food-new', $data->id, $data->is_new, $before);
            return \Lib::ajaxRespond(true, 'success', GalleryCat::getCategories());
        }
        return \Lib::ajaxRespond(false, 'Chưa nhập tiêu đề danh mục');
    }
}
