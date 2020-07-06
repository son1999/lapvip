<?php

namespace App\Imports;

use App\Models\Coupon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CouponsImport implements  ToModel,WithHeadingRow
{

    public function dateColumns(): array
    {
        return ['validuntil' => 'Y-m-d H:i', 'validfrom' => 'Y-m-d H:i'];
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if($row['code'] != null){
            $check = Coupon::where('code',$row['code'])->where('status','<',2)->first();
            if(empty($check)) {
                return new Coupon([
                    'code' => $row['code'],
                    'value' => $row['discount_percent'],
//                    'started' => strtotime($this->transformDate($row['validfrom'])),
                    'expired' => strtotime($this->transformDate($row['expired'])),
                    'type' => isset($row['type']) && $row['type'] ? $row['type'] : 'order',
                    'quantity' => isset($row['quantity'])?$row['quantity']:1,
                    'lang' => \Lib::getDefaultLang(),
                    'user_id' => \Auth::id(),
                    'object_id' => isset($row['object_id']) && $row['object_id'] ? $row['object_id'] : '',
                    'created' => time()
                ]);
            }else {
                $check->value = $row['discount_percent'];
                $check->expired = strtotime($this->transformDate($row['expired']));
                $check->type = isset($row['type']) && $row['type'] ? $row['type'] : 'order';
                $check->lang = \Lib::getDefaultLang();
                $check->quantity = isset($row['quantity'])?$row['quantity']:1;
                $check->user_id = \Auth::id();
                $check->object_id = isset($row['object_id']) && $row['object_id'] ? $row['object_id'] : '';
                $check->save();
            }
        }
    }

    public function transformDate($value, $format = 'Y-m-d H:i')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }
}
