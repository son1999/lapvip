<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentScenarios extends Model
{
    //
    protected $table = 'installment_scenarios';
    public $timestamps = false;

    public static function getPagersById($id){
        $param = \DB::table('installment_scenarios')
            ->where('id', $id)
            ->select('installment_scenarios.pagers_required')
            ->first();
        $data = !empty($param) ? $param->pagers_required : '';
        return $data;
    }
    public static function getInstallment(){
        return self::where('status', '>', 1)->get();
    }
    public function installment_scenarios(){
        return $this->hasOne(InstallmentDetail::class, 'installment_scenarios_id', 'id');
    }
}
