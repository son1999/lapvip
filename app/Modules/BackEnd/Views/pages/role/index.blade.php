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
                            <input type="text" name="fullname" class="form-control" placeholder="Tên Nhóm" value="{{ $search_data->fullname }}">
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
                        <th width="300">Tên Nhóm</th>
                        <th width="90">Xếp hạng</th>
                        <th>Quyền hạn</th>
                        <th width="100">Ngày tạo</th>
                        @if(\Lib::can($permission, 'edit'))
                            <th width="55">Sửa</th>
                        @endif
                        @if(\Lib::can($permission, 'delete'))
                            <th width="55">Xóa</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td align="center">{{ $item->id }}</td>
                        <td>{{ $item->title }}</td>
                        <td align="center">{{ $item->rank }}</td>
                        <td>
                            @if($item->id == 1)
                                <b class="text-danger">--- ALL ---</b>
                            @elseif(!empty($item->permit))
                                <a data-toggle="collapse" href="#role{{ $item->id }}" role="button" aria-expanded="false" aria-controls="role{{ $item->id }}">
                                    Chi tiết quyền</a>
                                <div class="collapse" id="role{{ $item->id }}">
                                    <div class="card card-body">
                                    @php($item->permit = json_decode($item->permit, 1))
                                    @foreach($item->permit as $k => $val)
                                        @php($val = implode(' - ', $val))
                                        <p><b>{{ $k }}:</b> <span class="text-success">{{ $val }}</span></p>
                                    @endforeach
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td align="center">{{ \Lib::dateFormat($item->created, 'd/m/Y') }}</td>
                        @if(\Lib::can($permission, 'edit'))
                            <td align="center">
                                @if($item->id != 1 && \Auth::user()->checkMyRank($item->rank))
                                    <a href="{{ route('admin.'.$key.'.edit', $item->id) }}" class="text-primary"><i class="fe-edit"></i></a>
                                @endif
                            </td>
                        @endif
                        @if(\Lib::can($permission, 'delete'))
                            <td align="center">
                                @if($item->id != 1 && \Auth::user()->checkMyRank($item->rank))
                                    <a href="{{ route('admin.'.$key.'.delete', $item->id) }}"  class="text-danger" onclick="return confirm('Bạn muốn xóa ?')"><i class="icon-trash icons"></i></a>
                                @endif
                            </td>
                        @endif
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