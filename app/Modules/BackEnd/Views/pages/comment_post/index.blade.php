@extends('BackEnd::layouts.default')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="mb-5"><h1>Quản trị {{ $site_title }}</h1></div>

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

        {!! Form::open(['url' => route('admin.'.$key), 'method' => 'get', 'id' => 'searchForm']) !!}
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fe-bookmark"></i></span>
                            <input type="text" name="title" class="form-control" placeholder="Tác giả" value="{{ $search_data->title }}">
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-navicon"></i></span>
                            <select id="cat_id" name="cat_id" class="form-control">
                                <option value="">-- Danh mục sản phẩm --</option>
                                @include('BackEnd::pages.category.option', [
                                    'options' => $products,
                                    'def' => old('type_id', $search_data->type_id)
                                ])
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" name="time_from" class="datepicker form-control" placeholder="Ngày đăng vào" autocomplete="off" value="{{ $search_data->created }}">
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-navicon"></i></span>
                            <select id="cat_id" name="cat_id" class="form-control">
                                <option value="">-- Trạng thái --</option>
                                <option value="1"{{ $search_data->status == 1 ? ' selected="selected"' : '' }}>Đang hiển thị</option>
                                <option value="2"{{ $search_data->status == 2 ? ' selected="selected"' : '' }}>Đang ẩn</option>
                                <option value="-1"{{ $search_data->status == -1 ? ' selected="selected"' : '' }}>Đã xóa</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>
            </div>
        </div>
        {!! Form::close() !!}

        <div class="card card-accent-info">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Danh sách
            </div>
            <div class="card-body">
                <table id="table-comment" class="table table-bordered table-striped table-responsive">
                    <thead>
                    <tr>
                        <th width="55">ID</th>
                        <th width="100">Tác giả</th>
                        <th>Bình luận</th>
                        <th width="250">Trả lời cho</th>
                        <th width="100">Rate</th>
                        <th width="100">Đã đăng vào</th>
                        @if(\Lib::can($permission, 'edit') || \Lib::can($permission, 'delete'))
                            <th width="55">Lệnh</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $k => $item)
                    <tr id="id{{$k}}">
                        <td>{{++$k}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{ $item['comment'] }}</td>
                        <td class="font-weight-bold">
                            @php
                                $temp = App\Models\Product::where('id', $item['type_id'])->value('title')
                            @endphp
                            <a target="_blank" href="{{route('product.detail',['safe_title' => \Illuminate\Support\Str::slug($temp),'id'=> $item['type_id']])}}">{{$temp}}</a></td>
                        <td>
                            <div class="product-rating">
                                <div class="list-rating">
                                    <div class="rating-item">
                                        <span class="star-rate star-@if(empty($item['rating']))0 @else{{$item['rating']}} @endif"></span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>{{ \Lib::dateFormat($item['created'], 'd/m/Y') }}</td>
                        <td align="center">
                            @if($item->status == 1)
                                <a href="javascript:void(0)" class="text-primary" onclick="shop.admin.updateStatus({{ $item->id }},false,'comment_post')" title="Đang hiển thị, Click để ẩn"><i class="fe-check-circle"></i></a>
                            @else
                                <a href="javascript:void(0)" class="text-secondary" onclick="shop.admin.updateStatus({{ $item->id }}, true,'comment_post')" title="Đang ẩn, Click để hiển thị"><i class="fe-check-circle"></i></a>
                            @endif
                            @if(\Lib::can($permission, 'delete'))
                                <a href="{{ route('admin.'.$key.'.delete', $item->id) }}"  class="btn text-danger" onclick="return confirm('Bạn muốn xóa ?')"><i class="icon-trash icons"></i></a>
                            @endif

                        </td>
                    </tr>

                    @endforeach
                    </tbody>
                </table>

                @if(empty($data) || $data->isEmpty())
                    <h4 align="center">Không tìm thấy dữ liệu phù hợp</h4>
                @else
                    <div class="pull-right">Tổng cộng: {{ $data->count() }} bản ghi / {{ $data->lastPage() }} trang</div>
                    {!! $data->links('BackEnd::layouts.pagin') !!}
                @endif
            </div>
        </div>
    </div>
</div>
@stop
@section('css')

<style>
    .product-rating .list-rating .rating-item .star-rate {
        margin: 5px 0;
        width: 90px;
        height: 14px;
        display: inline-block;
        background-image: url("../html-ohlala/images/star.png");
        background-position-y: center;
    }

    .product-rating .list-rating .rating-item .star-rate.star-5 {
    background-position-x: 0px;
    }

    .product-rating .list-rating .rating-item .star-rate.star-4 {
    background-position-x: -19px;
    }

    .product-rating .list-rating .rating-item .star-rate.star-3 {
    background-position-x: -38px;
    }

    .product-rating .list-rating .rating-item .star-rate.star-2 {
    background-position-x: -57px;
    }

    .product-rating .list-rating .rating-item .star-rate.star-1 {
    background-position-x: -76px;
    }

    .product-rating .list-rating .rating-item .star-rate.star-0 {
    background-position-x: -95px;
    }

    .rating-item .star-rate {
    margin: 5px 0;
    width: 90px;
    height: 14px;
    display: inline-block;
    background-image: url("../images/star.png");
    background-position-y: center;
    }

    .rating-item .star-rate.star-5 {
    background-position-x: 0px;
    }

    .rating-item .star-rate.star-4 {
    background-position-x: -19px;
    }

    .rating-item .star-rate.star-3 {
    background-position-x: -38px;
    }

    .rating-item .star-rate.star-2 {
    background-position-x: -57px;
    }

    .rating-item .star-rate.star-1 {
    background-position-x: -76px;
    }

    .rating-item .star-rate.star-0 {
    background-position-x: -95px;
    }
</style>
@stop
@section('js_bot')
{!! \Lib::addMedia('admin/js/gallery/vue.js') !!}

    <script>
        $(document).ready(function() {
            for(let index = 0; index < {{@$k}}; index++) {
                $(".reply" + index).click(function(e) {
                    $(".form-reply"+index).toggle();
                    e.preventDefault();
                });
            }
        });
    </script>
@endsection