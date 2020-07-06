@extends('BackEnd::layouts.default')

@section('content')
    {!! Form::open(['url' => route('admin.'.$key), 'method' => 'get', 'id' => 'searchForm']) !!}
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center mb-3">
                <div class="col-6 col-sm-4 col-md-2 col-xl mb-3 mb-xl-0">
                    <a class="btn btn-square btn-block btn-primary" href="{{route('admin.'.$key,['time_from' => \Carbon\Carbon::now()->startOfWeek()->format('d/m/Y'),
                                                                                                'time_to' => \Carbon\Carbon::now()->endOfWeek()->format('d/m/Y')
                                                                                                ])}}">This Week</a>
                </div>
                <div class="col-6 col-sm-4 col-md-2 col-xl mb-3 mb-xl-0">
                    <a class="btn btn-square btn-block btn-secondary" href="{{route('admin.'.$key,$data['thisMonth'])}}">This Month</a>
                </div>
                <div class="col-6 col-sm-4 col-md-2 col-xl mb-3 mb-xl-0">
                    <a class="btn btn-square btn-block btn-success" href="{{route('admin.'.$key,$data['lastWeek'])}}">Last Week</a>
                </div>
                <div class="col-6 col-sm-4 col-md-2 col-xl mb-3 mb-xl-0">
                    <a class="btn btn-square btn-block btn-warning" href="{{route('admin.'.$key,$data['lastMonth'])}}">Last Month</a>
                </div>
                <div class="col-6 col-sm-4 col-md-2 col-xl mb-3 mb-xl-0">
                    <a class="btn btn-square btn-block btn-danger" href="{{route('admin.'.$key,$data['thisYear'])}}">This Year</a>
                </div>
                <div class="col-6 col-sm-4 col-md-2 col-xl mb-3 mb-xl-0">
                    <a class="btn btn-square btn-block btn-info" href="{{route('admin.'.$key,$data['lastYear'])}}">Last Year</a>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-3">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" name="time_from" class="datepicker form-control" placeholder="Ngày bắt đầu" autocomplete="off" value="{{ $search_data->time_from }}">
                    </div>
                </div>
                <div class="form-group col-sm-3">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" name="time_to" class="datepicker form-control" placeholder="Ngày kết thúc" autocomplete="off" value="{{ $search_data->time_to }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>
        </div>
    </div>
    {!! Form::close() !!}
    <div class="card">
        <div class="card-header">
            <i class="fa fa-user"></i>Performance
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-header">TỔNG GIÁ TRỊ ĐƠN</div>
                        <div class="collapse show" id="collapseExample" style="">
                            <div class="card-body">
                                <p class="display-5">{{Lib::priceFormat($data['grossRevenue'])}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-header">DOANH THU NET</div>
                        <div class="collapse show" id="collapseExample" style="">
                            <div class="card-body">
                                <p class="display-5">{{Lib::priceFormat($data['netRevenue'])}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-header">TỔNG LƯỢNG ĐƠN</div>
                        <div class="collapse show" id="collapseExample" style="">
                            <div class="card-body">
                                <p class="display-5">{{Lib::priceFormat($data['numOfOrders'],' ')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-header">ĐƠN DONE</div>
                        <div class="collapse show" id="collapseExample" style="">
                            <div class="card-body">
                                <p class="display-5">{{Lib::priceFormat($data['numOfDoneOrders'],' ')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-6 col-md-4">
                    <div class="card">
                        <div class="card-header">TRUNG BÌNH ĐƠN</div>
                        <div class="collapse show" id="collapseExample" style="">
                            <div class="card-body">
                                <p class="display-5">{{Lib::priceFormat($data['avgValueOrder'])}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <i class="fa fa-user"></i>Leader Boards
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div>Top Customer - Total Spend</div>
                    <table class="table table-responsive-sm table-hover table-outline mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Customer</th>
                                <th>Orders</th>
                                <th>total spend</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($data['topCustomer'] as $item)
                            <tr>
                                <td>
                                    <div><a target="_blank" href="{{route('admin.customer.edit',['id' => $item->customer_id])}}">{{$item->fullname}}</a></div>
                                </td>
                                <td>
                                    <div class="clearfix">
                                        <div class="float-left">
                                            <strong>{{$item->total_orders}}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{Lib::priceFormat($item->total_spend)}}</strong>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="col-6">
                    <div>Top Coupon - Numbers Of Orders</div>
                    <table class="table table-responsive-sm table-hover table-outline mb-0">
                        <thead class="thead-light">
                        <tr>
                            <th>Coupon</th>
                            <th>Orders</th>
                            <th>Amount Discount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data['topCoupon'] as $item)
                            <tr>
                                <td>
                                    <div>{{$item->code}}</div>
                                </td>
                                <td>
                                    <div class="clearfix">
                                        <div class="float-left">
                                            <strong>{{$item->used_times}}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{Lib::priceFormat($item->amount)}}</strong>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="col-6 mt-5">
                    <div>Top Categories - Items Sold</div>
                    <table class="table table-responsive-sm table-hover table-outline mb-0">
                        <thead class="thead-light">
                        <tr>
                            <th>Category</th>
                            <th>Items Sold</th>
                            <th>Net Revenue</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data['topCategories'] as $item)
                            <tr>
                                <td>
                                    <div><a href="{{route('admin.category.edit',['id' => $item->cate_id])}}">{{$item->cate_title }}</a></div>
                                </td>
                                <td>
                                    <div class="clearfix">
                                        <div class="float-left">
                                            <strong>{{$item->total_items}}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{Lib::priceFormat($item->amount)}}</strong>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="col-6 mt-5">
                    <div>Top Products - Items Sold</div>
                    <table class="table table-responsive-sm table-hover table-outline mb-0">
                        <thead class="thead-light">
                        <tr>
                            <th>Product</th>
                            <th>Items sold</th>
                            <th>Net Revenue</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data['topProducts'] as $item)
                            <tr>
                                <td>
                                    <div><a href="{{route('admin.product.edit',['id' => $item->product_id])}}">{{$item->title}}</a></div>
                                </td>
                                <td>
                                    <div class="clearfix">
                                        <div class="float-left">
                                            <strong>{{$item->items_sold}}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{Lib::priceFormat($item->net_value)}}</strong>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop