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
                                <input type="text" name="code" class="form-control" placeholder="Mã giảm giá" value="{{ $search_data->code }}">
                            </div>
                        </div>
                        <div class="form-group col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-navicon"></i></span>
                                <select id="type" name="type" class="form-control">
                                    <option value="">-- Áp dụng cho --</option>
                                    @foreach($types as $k => $v)
                                        <option value="{{ $k }}" @if($search_data->type == $k) selected="selected" @endif>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModal">Thêm mới</button>
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#excelModal">Nhập từ excel</button>
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
                            <th width="150">Mã giảm giá</th>
                            <th>Giảm</th>
                            <th>Ngày có hiệu lực</th>
                            <th>Ngày hết hạn</th>
                            <th>Áp dụng</th>
                            <th>Số lần sử dụng</th>
                            <th>Đã dùng</th>
                            <th>Ngày tạo</th>
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
                                <td align="center">{{$item->id}}</td>
                                <td>{{$item->code}}</td>
                                <td>{{ \Lib::numberFormat($item->value)}}</td>
                                <td align="center">{{ \Lib::dateFormat($item->started, 'd/m/Y - H:i') }}</td>
                                <td align="center">{{ \Lib::dateFormat($item->expired, 'd/m/Y - H:i') }}</td>
                                <td align="center">{{ $item->type() }}</td>
                                <td align="center">{{ $item->quantity }}</td>
                                <td align="center">{{ $item->used_times }}</td>
                                <td align="center">{{ \Lib::dateFormat($item->created, 'd/m/Y') }}</td>
                                @if(\Lib::can($permission, 'edit'))
                                <td align="center"><a href="{{ route('admin.'.$key.'.edit', $item->id) }}" class="btn btn-primary mb-2"><i class="fe-edit"></i></a></td>
                                @endif
                                @if(\Lib::can($permission, 'delete'))
                                    <td align="center"><a href="{{ route('admin.'.$key.'.delete', $item->id) }}"  class="btn btn-danger mb-2" onclick="return confirm('Bạn muốn xóa ?')"><i class="icon-trash icons"></i></a></td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--/.col-->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    {!! Form::open(['url' => route('admin.'.$key.'.save.post'), 'files' => true,'id'=>'save_ajax_coupons']) !!}
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm mã giảm giá</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="number">Mã giảm giá</label>
                                    <input type="text" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" id="code" name="code" value="{{ old_blade('code') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="value">Giảm (<100 thì tính %)</label>
                                    <input type="text" class="form-control{{ $errors->has('value') ? ' is-invalid' : '' }}" id="value" name="value" value="{{ old_blade('value') }}" required>
                                </div>
                            </div>
                        </div>
                        {{--<div class="row">--}}
                            {{--<div class="col-sm-6">--}}
                                {{--<div class="form-group">--}}
                                    {{--<label for="started">Ngày kích hoạt</label>--}}
                                    {{--<input type="text" class="form-control{{ $errors->has('started') ? ' is-invalid' : '' }}" id="started" name="started" value="{{ old('started') }}" required autocomplete="off">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="expired">Ngày hết hạn</label>
                                    <input type="text" class="form-control{{ $errors->has('expired') ? ' is-invalid' : '' }}" id="expired" name="expired" value="{{ \Lib::dateFormat(old_blade('expired'),  'd/m/Y H:i') }}" required autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="expired">Số lần áp dụng</label>
                                    <input type="text" class="form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}" id="quantity" name="quantity" value="{{ old_blade('quantity') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="type">Áp dụng cho</label>
                                    <select onchange="changeAplly(this)" id="type" name="type" class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}">
                                        @foreach($types as $k => $v)
                                            <option value="{{ $k }}" @if(old_blade('type') == $k) selected="selected" @endif>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row wrap-object-id" style="{{old_blade('type') == 'order' ? 'display:none;' : '' }}">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="object_id">ID đối tượng áp dụng ( Danh mục, sản phẩm) Cách nhau dấu ,</label>
                                    <input type="object_id" class="form-control{{ $errors->has('object_id') ? ' is-invalid' : '' }}" id="object_id" name="object_id" value="{{ old_blade('object_id') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="save_coupons">Thêm mới</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        {{--Excel--}}
        <div class="modal fade" id="excelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    {!! Form::open(['url' => route('admin.'.$key.'.import.post'), 'files' => true,'id'=>'import_excel_coupons']) !!}
                    <div class="modal-header">
                        <h5 class="modal-title">Nhập mã giảm giá</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="excel_file">File excel</label>
                                    <input type="file" class="form-control" id="excel_file" name="excel_file" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="import_coupons">Import</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('admin/css/jquery.datetimepicker.min.css') }}?ver={{$def['version']}}">
@stop
@section('js_bot')
    <script type="text/javascript" src="{{ asset('admin/js/library/jquery.datetimepicker.min.js') }}?ver={{$def['version']}}"></script>
    <script>
        $(function () {
            $('#expired').datetimepicker({
                format:'d/m/Y H:i',
            });
            $('#started').datetimepicker({
                format:'d/m/Y H:i',
            });

            // $("#save_ajax_coupons").submit(function(e) {
            // e.preventDefault();
            // var fd = new FormData();
            // console.log(fd);

            // $.ajax({
            //     url: $(this).attr("action"),
            //     data: fd,
            //     processData: false,
            //     contentType: false,
            //     type: 'POST',
            //     success: function(data){
            //
            //     }
            // });
            // });

            $("#import_excel_coupons").submit(function(e) {
                // e.preventDefault();
                var fd = new FormData();
                fd.append( 'file', $( '#excel_file' )[0].files[0] );

                $.ajax({
                    url: $(this).attr("action"),
                    data: fd,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function(data){
                        window.location.reload();
                    }
                });

                return false;
            });

        });

    </script>
@stop
