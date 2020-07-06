<?php
/**
 * Created by PhpStorm.
 * Filename: PrdPriceTranformer.php
 * User: Thang Nguyen Nhan
 * Date: 20-Jul-19
 * Time: 04:15
 */

namespace App\Transformers;

use App\Libs\Lib;
use App\Models\ProductPrices;
use League\Fractal;

class PrdPriceTranformer extends Fractal\TransformerAbstract
{
    public function transform($prd)
    {
        $data = [
            'key_price'      => $prd->filter_ids,
            'price'          => Lib::priceFormat($prd->price,false),
            'price_strike'          => Lib::priceFormat($prd->price_strike,false),
//            'quantity'      => $prd->quantity
            'storage' => $prd->storage
        ];

        foreach($prd->filters as $item) {
            $data['obj'][] = [
                'id' => $item['id'],
                'title' => $item['title'],
            ];
        }

        return $data;
    }
}