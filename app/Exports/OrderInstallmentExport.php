<?php

namespace App\Exports;

use App\Models\InstallmentSuccess;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
class OrderInstallmentExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view():View
    {

        $cond = [];

        $data = InstallmentSuccess::with(['product' => function($query){
            $query->select('id', 'title', 'price');
        }, 'filters' =>function($query){
            $query->select('id', 'title', 'filter_cate_id');
        }, 'filters.filter_cate' => function($query){
            $query->select('id', 'title');
        },'installment_scenarios'])
            ->where('status', \request()->status)
            ->where($cond)
            ->orderByRaw('created DESC')->get();
        return view('BackEnd::pages.export.OrderInstallment', compact('data'));
    }
}
