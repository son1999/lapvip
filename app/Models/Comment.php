<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Comment extends Model {


    protected $table = 'comments';
    protected $fillable = ['id', 'uid', 'type', 'type_id', 'comment','aid', 'rep_comment', 'status'];
    public $timestamps = false;
    const TYPEPRODUCT = '1';    // 1: product

    public function customer(){
        return $this->belongsTo(Customer::class, 'uid', 'id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'type_id', 'id');
    }

    public function news(){
        return $this->belongsTo(News::class, 'type_id', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'aid', 'id');
    }

    public static function getCommentProductById($id) {
        return self::where([['status', 1], ['type_id', $id], ['type', self::TYPEPRODUCT]]);
    }


    public static function getAllCommentProductById($id) {
        return self::where([['status', 1], ['type_id', $id], ['type', self::TYPEPRODUCT]]);
    }
    
    public static function getSumRate($id) {
        return self::select('rating')
                ->where([['status', 1], ['type_id', $id], ['type', self::TYPEPRODUCT]])
                ->sum('rating');
        ;
    }

    public static function getCountRate($id) {
        return self::select('rating')
                ->where([['status', 1], ['type_id', $id], ['aid', ''], ['type', self::TYPEPRODUCT]])
                ->count('rating');
        ;
    }

    public static function pushComment($request){
        $comment = new  Comment();
        $comment->type_id = $request->type_id;
        $comment->name = $request->name;
        $comment->rating = $request->rate;
        $comment->comment = $request->content;
        $comment->status = 1;
        $comment->type = 1;
        $comment->created = strtotime(now());
        $comment->save();
        return $comment;
    }
}