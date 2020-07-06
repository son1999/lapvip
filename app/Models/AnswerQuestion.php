<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AnswerQuestion extends Model
{
    protected $table= 'answer_questions';
    protected $fillable =['id', 'uid','question', 'aid', 'answer', 'status'];
    public $timestamps = false;

    public function user(){
        return $this->hasOne(User::class, 'id', 'uid');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'uid', 'id');
    }

    public static function getProductQuestionById($id) {
        return self::where('product_id', $id)->where('status', 1)->where('qid', 0)->get();
    }

    public static function getanswerbyQuesPID($id){
        return self::where('product_id', $id)->where('status', 1)->where('qid', '>', 0)->get();
    }
}