<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class InstallmentDetail extends Model
{
    //
    protected $table = 'installment_detail';
    public $timestamps = false;

    public $fillable = ['properties'];

    public static function  returnProperties(Request $request,&$arr_unset)
    {
        $payment_title = $request->payment_title;
        $payment_image = $request->payment_image;
        $payment_image_name = $request->input('name_image');
        $arr_unset[] = 'name_image';
        $arr_unset[] = 'payment_title';
        $arr_unset[] = 'payment_image';
        $arr_unset[] = 'name_image';

        $arr_payment = [];
        if(!empty($payment_title)) {
            for($i=0;$i<count($payment_title);$i++) {
                $arr_unset[] = 'month_'.$i;
                $month = $request->input('month_'.$i);
                $arr_month = [];
                if(isset($month) && !empty($month)){
                    for($j = 0;$j<count($month);$j++) {
                        $arr_unset[] = 'interest_rate_'.$i.'-'.$j;
                        $arr_unset[] = 'conversion_fee_'.$i.'-'.$j;
                        $interest_rate = $request->input('interest_rate_'.$i.'-'.$j);
                        $conversion_fee = $request->input('conversion_fee_'.$i.'-'.$j);
                        $arr_props = [];
                        if(!empty($interest_rate) && !empty($conversion_fee)){
                            $arr_props[] = ['interest_rate' => $interest_rate,'conversion_fee' => $conversion_fee];
                        }
                        $arr_month[] = ['month' => $month[$j],'props' => $arr_props];
                    }
                }
                if (!empty($payment_image[$i])){
                    $fname = \ImageURL::makeFileName($payment_title[$i], $payment_image[$i]->getClientOriginalExtension());
                    \ImageURL::upload($payment_image[$i], $fname,  'installment_bank');
                }elseif(!empty($payment_image_name[$i])){
                    $fname = $payment_image_name[$i];
                }
                $arr_payment[] = ['payment_title' => $payment_title[$i], 'payment_image' => $fname,'month' => $arr_month];
            }
        }
        return json_encode($arr_payment, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    }
    public static function  returnProperties_scenarios(Request $request,&$arr_unset)
    {
        $company = $request->company;
        $image = $request->image_scenarios;
        $image_name = $request->input('name_image');
        $surcharge = $request->surcharge;
        $des = $request->des;
        $prepay = $request->prepay;
        $per_pay_mo = $request->per_pay_mo;

        $arr_unset[] = 'name_image';
        $arr_unset[] = 'company';
        $arr_unset[] = 'image_scenarios';
        $arr_unset[] = 'surcharge';
        $arr_unset[] = 'des';
        $arr_unset[] = 'prepay';
        $arr_unset[] = 'per_pay_mo';

        $arr_company = [];
        if(!empty($company)) {
            for($i=0;$i<count($company);$i++) {
                $arr_unset[] = 'pagers_'.$i;
                $pagers_required = $request->input('pagers_'.$i);
//                $arr_pagers = [];
//                if(isset($pagers_required) && !empty($pagers_required)){
//                    for($j = 0;$j<count($pagers_required);$j++) {
//                        $arr_pagers[] =  ['item' => $pagers_required];
//                    }
//                }
                if (!empty($image[$i])){
                    $fname = \ImageURL::makeFileName($company[$i], $image[$i]->getClientOriginalExtension());
                    \ImageURL::upload($image[$i], $fname,  'installment_scenarios');
                }elseif(!empty($image_name[$i])){
                    $fname = $image_name[$i];
                }
                $arr_company[] = ['company' => $company[$i], 'image' => $fname, 'surcharge' => $surcharge[$i], 'des' => $des[$i], 'prepay' => $prepay[$i], 'per_pay_mo' => $per_pay_mo[$i], 'pagers_required' => $pagers_required];
            }
        }
        return json_encode($arr_company, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    }

}
