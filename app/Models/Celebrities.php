<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Celebrities extends Model
{
    //
    public static $folder_upload = 'celebrities';
    protected $table = 'story_celebrities';
    public $timestamps = false;


}