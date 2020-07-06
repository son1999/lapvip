<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table= 'questions';
    protected $fillable =['id', 'product_id', 'uid','question', 'aid', 'answer', 'status'];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class, 'aid', 'id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'uid', 'id');
    }

    public static function getProductQuestionById($id) {
        return self::where('product_id', $id)->where('status', '>', 1)->where('qid', 0)->get();
    }

    public static function getanswerbyQuesPID($id){
        return self::with('user')->where('product_id', $id)->where('status', 1)->where('qid', '>', 0)->get();
    }
    public static function getQuestionProductById($id) {
        return self::where([['status', 1], ['product_id', $id]])->whereNull('answer')->where('qid', 0);
    }
}