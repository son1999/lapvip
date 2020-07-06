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
                            <input type="text" name="title" class="form-control" placeholder="Tiêu đề" value="{{ $search_data->title }}">
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
                        <th>ID</th>
                        <th>Tiêu đề</th>
                        <th>Phân loại</th>
                        <th>Độ ưu tiên</th>
                        <th>Giá trị</th>
                        <th>Ảnh</th>
                        <th width="100">Ngày tạo</th>
                        @if(\Lib::can($permission, 'edit') || \Lib::can($permission, 'delete'))
                            <th width="60">Lệnh</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>
                            <div class="mb-2">{{ $item->title }}</div>
                            {{--<div class="mb-2"><b>Bậc:</b> {{ $item->getLayerName() }}</div>--}}
                        </td>
                        <td>{{ $item->type() }}</td>
                        <td>{{ $item->sort}}</td>
                        <td>
                            <div class="mb-2">Giảm {{ $item->val() }}</div>
                            <div>
                                @php($detail = $details->where('customer_group_id', $item->id))
                                @if(!$detail->isEmpty())
                                <p><a href="javascript:void(0)" onclick="$('#other{{$item->id}}').slideToggle()">Xem chi tiết ({{$detail->count()}})</a></p>
                                <div id="other{{$item->id}}" style="display: none">
                                    @foreach($detail as $d)
                                        <p>
                                            {{$d->hotel->name}}{{!empty($d->room) ? ' -'.$d->room->name : ''}} giảm
                                            @if($d->percent > 0)
                                                {{ $d->percent }}%
                                            @else
                                                {{ \Lib::priceFormatEdit($d->bonus)['price']}}<sup class="text-danger">đ</sup>
                                            @endif
                                        </p>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if(!empty($item->image))
                                <img src="{{ $item->getImageUrl('small') }}" width="30">
                            @else
                                ---
                            @endif
                        </td>
                        <td>{{ \Lib::dateFormat($item->created, 'd/m/Y') }}</td>
                        @if(\Lib::can($permission, 'edit') || \Lib::can($permission, 'delete'))
                            <td>
                            @if(\Lib::can($permission, 'edit'))
                                <a href="{{ route('admin.'.$key.'.edit', $item->id) }}" class="btn btn-primary mb-2"><i class="fe-edit"></i></a>
                            @endif
                            @if(\Lib::can($permission, 'delete') && $item->id != 1)
                                <div>
                                    <a href="{{ route('admin.'.$key.'.delete', $item->id) }}"  class="btn btn-danger" onclick="return confirm('Bạn muốn xóa ?')"><i class="icon-trash icons"></i></a>
                                </div>
                            @endif
                            </td>
                        @endif
                    </tr>
                    @endforeach
                    </tbody>
                </table>



                {!! $data->links('BackEnd::layouts.pagin') !!}
            </div>
        </div>
    </div>
    <!--/.col-->
</div>
@stop