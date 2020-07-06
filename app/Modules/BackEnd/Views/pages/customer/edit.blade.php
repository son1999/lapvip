@extends('BackEnd::layouts.default')

@section('content')
<style>
    .p-item {
    width: 100%;
    position: relative;
    }

    .p-item .figure {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 100%;
    overflow: hidden;
    }

    .p-item .figure img {
    -o-object-fit: cover;
        object-fit: cover;
    max-width: 100%;
    min-width: 100%;
    -webkit-transition: -webkit-transform .5s ease;
    transition: -webkit-transform .5s ease;
    transition: transform .5s ease;
    transition: transform .5s ease, -webkit-transform .5s ease;
    }

    .p-item .figure:after {
    content: '\A';
    background: rgba(0, 0, 0, 0.25);
    position: absolute;
    height: 100%;
    width: 100%;
    left: 0;
    top: 0;
    bottom: 0;
    right: 0;
    opacity: 0;
    -webkit-transition: all 0.4s ease-in-out 0s;
    transition: all 0.4s ease-in-out 0s;
    }

    .p-item .info .title {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    color: #333333;
    }

    .p-item .info .price {
    font-size: 14px;
    }

    .p-item:hover {
    cursor: pointer;
    }

    .p-item:hover .figure::after {
    opacity: 1;
    }

    .p-item:hover img {
    -webkit-transform: scale(1.1);
            transform: scale(1.1);
    }

    .p-item .discount {
    position: absolute;
    top: -15px;
    left: -11px;
    display: inline-block;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #f26149;
    color: #ffffff;
    }

    .p-item .discount span {
    font-size: 14px;
    font-weight: 400;
    margin-top: 10px;
    margin-left: 5px;
    display: inline-block;
    font-family: 'Roboto Condensed', sans-serif;
    }

    .item-2 .info .title {
    max-height: 42px;
    line-height: 1.5;
    -webkit-line-clamp: 2;
    font-weight: 400 !important;
    }

    .item-2 .info .price {
    font-size: 14px;
    font-weight: 700;
    }

    .item-2 .info .price .standard {
    font-weight: 400;
    text-decoration: line-through;
    padding-left: 1rem;
    }

    .item-1 .info .title {
    max-height: 63px;
    line-height: 1.5;
    font-weight: 700;
    -webkit-line-clamp: 3;
    }

    .item-1 .info .price {
    color: #555555;
    }

    .item-1 .info .price .standard {
    display: none;
    }

</style>
<div class="row">
    <div class="col-sm-6">
        @if(old_blade('editMode'))
            {!! Form::open(['url' => route('admin.'.$key.'.edit.post', old_blade('id')) , 'files' => true]) !!}
        @else
            {!! Form::open(['url' => route('admin.'.$key.'.add.post') , 'files' => true]) !!}
        @endif
        <div class="card">
            <div class="card-header">
                <i class="fa fa-user"></i>Sửa thông tin khách hàng <b>{{ old_blade('fullname') }}</b>
            </div>
            <div class="card-body">
                @if( count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{!! $error !!}</div>
                        @endforeach
                    </div>
                @endif
                @if (session('status'))
                    <div class="alert alert-success">
                        {!! session('status') !!}
                    </div>
                @endif

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="fullname">Họ và tên</label>
                            <input type="text" class="form-control{{ $errors->has('fullname') ? ' is-invalid' : '' }}" id="fullname" name="fullname" value="{{ old_blade('fullname') }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="email">Email </label>
                            <input type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" value="{{ old_blade('email') }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="phone">Số điện thoại </label>
                            <input type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" id="phone" name="phone" value="{{ old_blade('phone') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="password">Mật khẩu </label>
                            <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="password_confirm">Nhập lại mật khẩu </label>
                            <input type="password" class="form-control{{ $errors->has('password_confirm') ? ' is-invalid' : '' }}" id="password_confirm" name="password_confirm">
                        </div>
                    </div>
                </div>

                    <div class="card">
                        <div class="card-header">Phân nhóm</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        @foreach($group as $r)
                                            <div class="checkbox">
                                                <label for="checkbox{{ $r->id }}">
                                                    <input type="checkbox" id="checkbox{{ $r->id }}" name="groups[]" value="{{ $r->id }}"{{ in_array($r->id, old('groups', isset($data->groups) && $data->groups ? $data->groups->pluck('id')->all() : []))?' checked':'' }}>&nbsp; {{ $r->title }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Cập nhật</button>
                <a class="btn btn-sm btn-danger" href="{{ redirect()->back()->getTargetUrl() }}"><i class="fa fa-ban"></i> Hủy bỏ</a>
            </div>
        </div>
		{!! Form::close() !!}
        </div>
        @if(isset($list_orders) && !empty($list_orders))
        <div class="col-sm-6 form_notice">
            <div class="card info_form_notice">
                <div class="card-header">
                    Lịch sử đơn hàng
                </div>
                <div class="card-body">
                    <div class="pro5-section-form">
                        {{--<div class="personal_infor">--}}
                        {{--<div class="wrap-input col-md-6 col-sm-6 col-12">--}}
                        {{--{!! Form::open(['url' => route('admin.customer.edit',['id' => $data->id]),'method'=>'get','id'=>'searchForm']) !!}--}}
                        {{--<div class="form-group">--}}
                        {{--<label for="show_type">Hiển thị</label>--}}
                        {{--<select name="show_type" class="form-control" onchange="submit();">--}}
                        {{--<option value="">{{ __('site.tatcadonhang') }}</option>--}}
                        {{--@foreach($booking_types as $k => $v)--}}
                        {{--<option @if(@$data_search['show_type'] == $k) selected @endif value="{{$k}}">{{$v}}</option>--}}
                        {{--@endforeach--}}
                        {{--</select>--}}
                        {{--</div>--}}

                                            {{--{!! Form::close() !!}--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                        <div class="wrap-list">
                            @foreach($list_orders as $order)
                                <div class="wrap-order">
                                    <div class="row order-head">
                                        <div class="col-lg-6">
                                            <div class="order-alias">
                                                <span>{{$order->type()}} <b class="cl-red-moon">#{{$order->code}}</b></span>
                                                <span>{{ __('site.datngay') }} {{\Lib::dateFormat($order->created,'d/m/Y - H:i')}}</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="order-btns">
                                                <a target="_blank" href="{{route('admin.order.view',['id' => $order->id])}}">{{ __('site.chitiet') }}</a>
                                                {{--<a href="#">Hủy đơn hàng</a>--}}
                                                <span class="badge badge-pill badge-secondary">{{$order->status()}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="orders-products">
                                        <div class="">
                                            @if($order->type == 'order')
                                                <table class="table table-borderless">
                                                    <tbody>
                                                    @foreach($order->items as $itm)
                                                        <tr>
                                                            <td >{{$itm->name}}</td>
                                                            <td width="150">{{ __('site.soluong') }}: {{$itm->quantity}}</td>
                                                            <td>{{Lib::priceFormat($itm->price*$itm->quantity)}}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <td colspan="2">Phí giao hàng</td>
                                                        <td>{{\Lib::priceFormatEdit($order->fee_shipping)['price']}} đ </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">{{ __('site.tongtien') }}</td>
                                                        <td>{{\Lib::priceFormatEdit($order->total_price + $order->fee_shipping)['price']}} đ </td>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            @else
                                                <ul class="book_table_info">
                                                    <li>{{ __('site.thoigiansudung') }}: <b>{{ \Lib::dateFormat($order->startTime, $format = 'd/m/Y - H:i', true, true) }}</b></li>
                                                    <li class="p-0">{{ __('site.soluong') }}: <b>{{ $order->adult }}</b> {{ __('site.nguoilon') }} {!!  $order->child > 0 ? ', <b>'.$order->child.'</b> '.__('site.treem'):'' !!}</li>
                                                    <li>{{ __('site.hovaten') }}: {{ $order->customer_name }}</li>
                                                    <li class="p-0">{{ __('site.sodienthoai') }}: <b>{{ $order->phone }}</b></li>
                                                </ul>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div>{{$list_orders->links('BackEnd::layouts.pagin')}}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @if(isset($prd_history))
    <form id="searchForm">
        <div class="card">
            <div class="card-header"> Sản phẩm đã xem </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($prd_history as $phis)
                    @foreach ($phis->viewed as $viewed)
                    <div class="col-12 col-sm-6 col-lg-3 mb-4">
                        <div class="p-item item-1">
                            <a href="{{url('/',['safe_title' => \Illuminate\Support\Str::slug($viewed->title).'-p'.$viewed->id])}}" class="figure">
                                <img data-src="{{$viewed->getImageUrl('medium')}}" class="lazyload" alt="">
                            </a>
                            <div class="info">
                                <a href="#" class="title">{{$viewed->title}}</a>
                                <span class="price d-block mt-2"> <span class="standard">300000đ</span> </span>
                            </div>
                            @if($viewed->priceStrike)
                                <div class="discount"><span>-{{100- round($viewed->price/$viewed->priceStrike*100)}}%</span></div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    @endforeach
                </div>
            </div>
            <div class="card-body">
                @if(empty($prd_history) || $prd_history->isEmpty())
                    <h4 align="center">Không tìm thấy dữ liệu phù hợp</h4>
                @else
                    <div class="pull-right">Tổng cộng: {{ $prd_history->count() }} bản ghi / {{ $prd_history->lastPage() }} trang</div>
                    {!! $prd_history->links() !!}
                @endif
            </div>
        </div>
    </form>
    @endif
</div>
@stop