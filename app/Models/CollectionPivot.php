<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CollectionPivot extends Model
{


    protected $table = 'collection_cate_pivot';
    public $timestamps = false;
    protected $fillable = ['collect_id', 'cat_id'];

}