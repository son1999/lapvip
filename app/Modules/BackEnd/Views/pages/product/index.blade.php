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
                            <span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
                            <input type="text" name="id" class="form-control" placeholder="ID" value="{{ $search_data->id }}">
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fe-bookmark"></i></span>
                            <input type="text" name="title" class="form-control" placeholder="Tiêu đề" value="{{ $search_data->title }}">
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" name="time_from" class="datepicker form-control" placeholder="Ngày đăng kí từ" autocomplete="off" value="{{ $search_data->time_from }}">
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" name="time_to" class="datepicker form-control" placeholder="Ngày đăng kí đến" autocomplete="off" value="{{ $search_data->time_to }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-language"></i></span>
                            <select id="lang" name="lang" class="form-control">
                                <option value="">-- Chọn ngôn ngữ --</option>
                                @foreach($langOpt as $k => $v)
                                    <option value="{{ $k }}" @if($search_data->lang == $k) selected="selected" @endif>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-list"></i></span>
                            <select name="status" class="form-control">
                                <option value="">-- Chọn trạng thái --</option>
                                <option value="2"{{ $search_data->status == 2 ? ' selected="selected"' : '' }}>Đang hiển thị</option>
                                <option value="1"{{ $search_data->status == 1 ? ' selected="selected"' : '' }}>Đang ẩn</option>
                                <option value="-1"{{ $search_data->status == -1 ? ' selected="selected"' : '' }}>Đã xóa</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-list"></i></span>
                            <select id="type" name="type" class="form-control">
                                <option value="">-- Phân loại --</option>
                                <option value="2"{{ $search_data->type == 2 ? ' selected="selected"' : '' }}>Món mới</option>
                                <option value="1"{{ $search_data->type == 1 ? ' selected="selected"' : '' }}>Món ưa thích</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-list"></i></span>
                            <select id="cat_id" name="cat_id" class="form-control">
                                <option value="">-- Chọn danh mục --</option>
                                @include('BackEnd::pages.category.option', [
                                    'options' => $catOpt,
                                    'def' => old('cat_id', $search_data->cat_id)
                                ])
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
                <table class="table table-bordered table-striped table-responsive">
                    <thead>
                    <tr>
                        <th width="60">ID</th>
                        <th width="60">Sort</th>
                        <th>Tiêu đề</th>
                        <th>Danh mục</th>
                        <th width="140">Giá</th>
                        <th width="100">Ngôn ngữ</th>
                        <th width="125">Ảnh</th>
                        <th width="100">Ngày tạo</th>
                        @if(\Lib::can($permission, 'edit') || \Lib::can($permission, 'delete'))
                            <th width="100">Bán chạy</th>
                            {{--<th width="55">HOT</th>--}}
                            <th width="55">Cao cấp</th>
                            <th width="80">Liên hệ</th>
                            <th width="55">Lệnh</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td align="center">{{ $item->id }}</td>
                        <td align="center">{{ $item->sort }}</td>
                        <td>
                            <b>{{ $item->title }}</b>
                            <div class="mt-2 font-sm"><i>{{ $item->sapo }}</i></div>
                        </td>
                        <td>{{ !empty($item->category) ? $item->category->title : '---' }}</td>
                        <td align="right">
                            <b>{{ $item->price_format() }}</b><br>
                            <s>{{ $item->price_format(true) }}</s>
                        </td>
                        <td>{{ $item->lang() }}</td>
                        <td align="center">
                            @if($item->image != '')
                                <img src="{{ $item->getImageUrl('medium') }}" width="100" />
                            @endif
                        </td>
                        <td align="center">{{ \Lib::dateFormat($item->created, 'd/m/Y H:i:s') }}</td>
                        @if(\Lib::can($permission, 'edit') || \Lib::can($permission, 'delete'))
                            <td align="center">
                                @if(\Lib::can($permission, 'edit'))
                                    <div class="mb-2">
                                        @if($item->is_selling == 1)
                                            <a href="javascript:void(0)" class="text-danger" onclick="shop.admin.setSelling({{ $item->id }},false,'product')" title="Đang đánh dấu là Sản phẩm Bán Chạy, bấm để tắt"><i style="color: #8e44ad" class="icon-fire icons"></i></a>
                                        @else
                                            <a href="javascript:void(0)" class="text-secondary" onclick="shop.admin.setSelling({{ $item->id }}, true,'product')" title="Đang bình thường, Click để đánh dấu Sản phẩm Bán Chạy"><i class="icon-fire icons"></i></a>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td align="center">
                                @if(\Lib::can($permission, 'edit'))
                                    <div class="mb-2">
                                        @if($item->is_height_class == 1)
                                            <a href="javascript:void(0)" class="text-danger" onclick="shop.admin.setHeightClass({{ $item->id }},false,'product')" title="Đang hiển thị mặt hàng Cao Cấp, bấm để tắt"><i style="color: #27ae60" class="icon-star icons"></i></a>
                                        @else
                                            <a href="javascript:void(0)" class="text-secondary" onclick="shop.admin.setHeightClass({{ $item->id }}, true,'product')" title="Đang bình thường, Click để hiển thị ở vị trí mặt hàng Cao Cấp"><i class="icon-star icons"></i></a>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td align="center">
                                @if(\Lib::can($permission, 'edit'))
                                    <div class="mb-2">
                                        @if($item->out_of_stock == 1)
                                            <a href="javascript:void(0)" class="text-danger" onclick="shop.admin.setOutOfStock({{ $item->id }},false,'product')" title="Liên hệ hay chưa"><i style="color: red" class="icon-power icons"></i></a>
                                        @else
                                            <a href="javascript:void(0)" class="text-secondary" onclick="shop.admin.setOutOfStock({{ $item->id }}, true,'product')" title="Liên hệ hay chưa"><i class="icon-power icons"></i></a>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            {{--<td align="center">--}}
                                {{--@if(\Lib::can($permission, 'edit'))--}}
                                    {{--<div class="mb-2">--}}
                                        {{--@if($item->hot == 1)--}}
                                            {{--<a href="javascript:void(0)" class="text-danger" onclick="shop.admin.setHot({{ $item->id }},false,'product')" title="Đang hiển thị vị trí HOT, bấm để tắt"><i class="icon-fire icons"></i></a>--}}
                                        {{--@else--}}
                                            {{--<a href="javascript:void(0)" class="text-secondary" onclick="shop.admin.setHot({{ $item->id }}, true,'product')" title="Đang bình thường, Click để hiển thị ở vị trí HOT"><i class="icon-fire icons"></i></a>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                {{--@endif--}}
                            {{--</td>--}}
                            <td align="center">
                                @if(\Lib::can($permission, 'edit'))
                                    <div class="mb-2">
                                        @if($item->status == 2)
                                            <a href="javascript:void(0)" class="text-primary" onclick="shop.admin.updateStatus({{ $item->id }},false,'product')" title="Đang hiển thị, Click để ẩn"><i class="fe-check-circle"></i></a>
                                        @else
                                            <a href="javascript:void(0)" class="text-secondary" onclick="shop.admin.updateStatus({{ $item->id }}, true,'product')" title="Đang ẩn, Click để hiển thị"><i class="fe-check-circle"></i></a>
                                        @endif
                                    </div>

                                    <div><a href="{{ route('admin.'.$key.'.edit', $item->id) }}" class="text-primary"><i class="fe-edit"></i></a></div>
                                @endif
                                @if(\Lib::can($permission, 'delete'))
                                        <div class="mt-2"><a href="{{ route('admin.'.$key.'.delete', $item->id) }}"  class="text-danger" onclick="return confirm('Bạn muốn xóa ?')"><i class="icon-trash icons"></i></a></div>
                                @endif
                            </td>
                        @endif
                    </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="pull-right">Tổng cộng: {{ $data->count() }} bản ghi / {{ $data->lastPage() }} trang</div>

                {!! $data->links('BackEnd::layouts.pagin') !!}
            </div>
        </div>
    </div>
    <!--/.col-->
</div>
@stop