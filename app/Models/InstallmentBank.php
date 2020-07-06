<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentBank extends Model
{
    //
    protected $table = 'installment_bank';
    public $timestamps = false;

    public function installment_payment_bank(){
        return $this->hasOne(InstallmentDetail::class, 'installment_id', 'id');
    }
    public function getImageUrl($size = 'original'){
        return \ImageURL::getImageUrl($this->image, 'installment_bank', $size);
    }

}
