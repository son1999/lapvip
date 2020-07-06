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
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input type="text" name="user_name" class="form-control" placeholder="Tên đăng nhập" value="{{$search_data['user_name']}}">
                        </div>
                    </div>

                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fe-mail"></i></span>
                            <input type="text" name="email" class="form-control" placeholder="Email" value="{{$search_data['email']}}">
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                            <input type="text" name="phone" class="form-control" placeholder="Số điện thoại" value="{{$search_data['phone']}}">
                        </div>
                    </div>

                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-gears"></i></span>
                            <select id="status" name="status" class="form-control">
                                <option value="">-- Trạng thái --</option>
                                @foreach($statusOpt as $k => $v)
                                    <option value="{{ $k }}"{{$search_data->status != '' && $search_data->status == $k ? ' selected="selected"' : ''}}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-gears"></i></span>
                            <select id="role" name="role" class="form-control">
                                <option value="">-- Quyền hạn --</option>
                                @foreach($roles as $r)
                                    <option value="{{ $r->id }}" {{$search_data->role != '' && $search_data->role == $r->id ? ' selected="selected"' : ''}}>{{ $r->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" name="time_from" class="dateptimeicker form-control" placeholder="Từ ngày" autocomplete="off" value="{{ $search_data->time_from }}">
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" name="time_to" class="dateptimeicker form-control" placeholder="Đến ngày" autocomplete="off" value="{{ $search_data->time_to }}">
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
                        <th width="150">Tên đăng nhập</th>
                        <th>Thông tin cá nhân</th>
                        <th>Vai trò</th>
                        <th width="200">Đăng nhập</th>
                        <th width="120">Trạng thái</th>
                        <th width="100">Ngày ĐK</th>
                        <th width="55">Log</th>
                        @if(\Lib::can($permission, 'edit'))
                            <th width="55">KH</th>
                        @endif
                        @if(\Lib::can($permission, 'delete') || \Lib::can($permission, 'edit'))
                            <th width="55">Lệnh</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $user)
                    <tr>
                        <td align="center">{{ $user->id }}</td>
                        <td><b>{{ $user->user_name }}</b></td>
                        <td>
                            <div><b>N:</b> {{ $user->fullname }}</div>
                            <div><b>E:</b> {{ $user->email }}</div>
                            <div><b>T:</b> {{ $user->phone }}</div>
                        </td>
                        <td>
                            @if($user->isRoot())
                                <span class="font-weight-bold text-warning">-- BIG ROOT --</span>
                            @else
                                @foreach($user->roles as $role)
                                    <div class="mb-1">{{ $role->title }}</div>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            <div><b>IP:</b> {{ $user->last_login_ip }}</div>
                            <div><b>Lúc:</b> {{ \Lib::dateFormat($user->last_login, 'd/m/Y H:i:s') }}</div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $user->getStatusClass() }}">{{ $user->getStatusText() }}</span>

                            @if($user->last_logout > 0)
                                <span title="{{\Lib::dateFormat($user->last_logout, 'd/m/Y')}}">{{ \Lib::dateFormat($user->last_logout, 'H:i:s') }}</span>
                            @elseif($user->last_active > 0)
                                <span title="{{\Lib::dateFormat($user->last_active, 'd/m/Y')}}">{{ \Lib::dateFormat($user->last_active, 'H:i:s') }}</span>
                            @endif

                        </td>
                        <td align="center">{{ \Lib::dateFormat($user->created, 'd/m/Y H:i:s') }}</td>
                        <td align="center"><a href="{{ route('admin.'.$key.'.log', $user->id) }}" class="text-primary"><i class="icon-magnifier icons"></i></a></td>
                        @if(\Lib::can($permission, 'edit'))
                            <td align="center">
                                @if (($user->id != \Auth::id()) && (!$user->isRoot() || \Auth::id() == 1) && \Auth::user()->biggerThanYou($user->id))
                                    <a href="javascript:void(0)" class="{{ $user->active > 0 ? 'text-primary' : 'text-secondary' }}" title="{{ $user->active > 0 ? 'Đang kích hoạt' : 'Chưa kích hoạt' }}, click để thay đổi" onclick="shop.admin.activeUser({{$user->id}}, {{ $user->active > 0 ? 0 : 1 }})"><i class="icon-check icons"></i></a>
                                @endif
                            </td>
                        @endif
                        @if(\Lib::can($permission, 'delete') || \Lib::can($permission, 'edit'))
                            <td align="center">
                                @if ( ((!$user->isRoot() || \Auth::id() == 1) && \Auth::user()->biggerThanYou($user->id)) || $user->id == \Auth::id())
                                    <div class="mb-3"><a href="{{ route('admin.'.$key.'.edit', $user->id) }}" class="text-primary"><i class="fe-edit"></i></a></div>
                                @endif

                                    @if (!$user->isRoot() && $user->id != \Auth::id() && $user->status != -1 && \Auth::user()->biggerThanYou($user->id))
                                    <a href="{{ route('admin.'.$key.'.delete', $user->id) }}" onclick="return confirm('Bạn muốn xóa ?')" class="text-danger"><i class="icon-trash icons"></i></a>
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