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
                                <input type="text" name="title" class="form-control" placeholder="Title" value="{{ $search_data->title }}">
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
                    <table class="table table-bordered table-striped table-responsive">
                        <thead>
                        <tr>
                            <th width="55">ID</th>
                            <th>Title</th>
                            <th width="150">Mã giảm giá</th>
                            <th>Giảm</th>
                            <th>Ngày có hiệu lực</th>
                            <th>Ngày hết hạn</th>
                            <th>Áp dụng</th>
                            <th>Số lần sử dụng</th>
                            <th>Đã dùng</th>
                            <th>Ngày tạo</th>
                            @if(\Lib::can($permission, 'edit') || \Lib::can($permission, 'delete'))
                                <th width="55">Lệnh</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td align="center">{{$item->id}}</td>
                                <td>{{$item->title}}</td>
                                @if(!empty($item->coupon))
                                    <td>{{$item->coupon->code}}</td>
                                    <td>{{ \Lib::numberFormat($item->coupon->value)}}</td>
                                    <td align="center">{{ \Lib::dateFormat($item->coupon->started, 'd/m/Y - H:i') }}</td>
                                    <td align="center">{{ \Lib::dateFormat($item->coupon->expired, 'd/m/Y - H:i') }}</td>
                                    <td align="center">{{ $item->coupon->type() }}</td>
                                    <td align="center">{{ $item->coupon->quantity }}</td>
                                    <td align="center">{{ $item->coupon->used_times }}</td>
                                @else
                                    <td>---</td>
                                    <td>---</td>
                                    <td>---</td>
                                    <td>---</td>
                                    <td>---</td>
                                    <td>---</td>
                                    <td>---</td>
                                @endif

                                <td align="center">{{ \Lib::dateFormat($item->created, 'd/m/Y') }}</td>
                                <td align="center">
                                    @if(\Lib::can($permission, 'edit'))
                                        @if($item->status == 2)
                                            <a href="javascript:void(0)" class="text-primary" onclick="shop.admin.updateStatus({{ $item->id }},false,'spin')" title="Đang hiển thị, Click để ẩn"><i class="icon-eye icons"></i></a>
                                        @else
                                            <a href="javascript:void(0)" class="text-secondary" onclick="shop.admin.updateStatus({{ $item->id }}, true,'spin')" title="Đang ẩn, Click để hiển thị"><i class="icon-eye icons"></i></a>
                                        @endif
                                        <a href="{{ route('admin.'.$key.'.edit', $item->id) }}" class="btn text-primary"><i class="icon-pencil icons"></i></a>
                                    @endif
                                    @if(\Lib::can($permission, 'delete'))
                                        <a href="{{ route('admin.'.$key.'.delete', $item->id) }}"  class="btn text-danger" onclick="return confirm('Bạn muốn xóa ?')"><i class="icon-trash icons"></i></a>
                                    @endif
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
