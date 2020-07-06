<?php

namespace App\Modules\BackEnd\Controllers;

use Illuminate\Http\Request;

use App\Models\UploadFile as THIS;

class FileController extends BackendController
{
    protected $timeStamp = 'created';

    //config controller, ez for copying and paste
    public function __construct(){
        parent::__construct(new THIS());
        $this->bladeAdd = 'add';
        $this->registerAjax('upload-file', 'ajaxUploadFile', 'upload');
    }

    protected function ajaxUploadFile(Request $request){
        if ($request->hasFile('Filedata')) {
            $image = $request->file('Filedata');
            if ($image->isValid()) {
                $key = 'file';
                $err = '';
                $fname = basename($image->getClientOriginalName(), '.'.$image->getClientOriginalExtension());
                $fname = \ImageURL::makeFileName($fname, $image->getClientOriginalExtension());
                $type = $image->getClientMimeType();
                $size = $image->getClientSize();
                $image = \ImageURL::upload($image, $fname, $key, $err);
                if($image){
                    $images = new THIS();
                    $images->name = $fname;
                    $images->size = $size;
                    $images->type = $type;
                    $images->created = time();
                    $images->save();

                    \MyLog::do()->add('upload', $images->id, $images);

                    return \Lib::ajaxRespond(true, 'ok', [
                        'id' => $images->id,
                        'name' => $images->name,
                        'image' => $images->getImageUrl('large'),
                        'image_sm' => $images->getImageUrl('small')
                    ]);
                }
                return \Lib::ajaxRespond(false, 'Upload ảnh thất bại! '.$err);
            }
            return \Lib::ajaxRespond(false, 'File không hợp lệ!');
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy ảnh!');
    }
}
