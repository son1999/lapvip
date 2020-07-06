<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadFile extends Model
{
    //
    protected $table = 'images';
    public $timestamps = false;

    public function getImageUrl($size = 'original'){
        return \ImageURL::getImageUrl($this->name, 'file', $size);
    }
}
