@extends('BackEnd::layouts.default')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            {!! Form::open(['url' => route('admin.'.$key.'.add.post'), 'files' => true]) !!}
            @if( count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{!! $error !!}</div>
                    @endforeach
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <i class="fe-menu"></i>THÔNG TIN KHO HÀNG
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">Tên cửa hàng</label>
                                <input type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" value="{{ old('title') }}" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="location">Địa chỉ</label>
                                <input type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="location" name="location" value="{{ old('location') }}" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="phone">Liên hệ</label>
                                <input type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" id="phone" name="phone" value="{{ old('phone') }}" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="title">Khu Vực / Thành phố</label>
                                <select name="province_id" id="province_id" class="form-control">
                                    <option value="" selected disabled="disabled">Chọn khu vực của bạn</option>
                                    @foreach($province as $provin)
                                        <option value="{{$provin->id}}" >{{$provin->Name_VI}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="title">Quận / Huyện</label>
                                <select name="district_id" id="district_id" class="form-control @error('district_id') is-invalid  @enderror">
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="location">Google Maps Address</label>
                                <input type="text" class="form-control{{ $errors->has('map') ? ' is-invalid' : '' }}" id="map" name="map" value="" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" id="inFo_Supports">
                <div class="card-header">
                    <i class="fe-menu"></i>THÔNG TIN NHÂN VIÊN HỖ TRỢ
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="">Title</label>
                        </div>
                        <div class="col-sm-3">
                            <label for="">Facebook Link</label>
                        </div>
                        <div class="col-sm-2">
                            <label for="">Phone / Zalo</label>
                        </div>
                    </div>
                    <div class="row mb-1" v-for="(item, key) in data.val">
                        <div class="col-sm-4">
                            <input class="col-md-12 p-1" type="text" v-bind:name="'title_sp[]'"   autocomplete="off">
                        </div>
                        <div class="col-sm-3">
                            <input class="col-md-12 p-1" type="text" v-bind:name="'face_sp[]'"   autocomplete="off">
                        </div>
                        <div class="col-sm-3">
                            <input class="col-md-12 p-1" type="phone" v-bind:name="'phone_sp[]'"  onkeypress="return shop.numberOnly()" onfocus="this.select()"  autocomplete="off">
                        </div>
                            <span @click="addLine" class="btn btn-success ml-2 mb-1"><i class="fa fa-plus" aria-hidden="true"></i></span>
                            <span @click="trashLine(data.val, key)" v-if="isShow" class="btn btn-danger ml-2 mb-1"><i class="fas fa-trash"></i></span>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Thêm mới</button>
                &nbsp;&nbsp;
                <a class="btn btn-sm btn-danger" href="{{ redirect()->back()->getTargetUrl() }}"><i class="fa fa-ban"></i> Hủy bỏ</a>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@stop

@section('css')
@stop

@section('js_bot')
    <script>
        var supports = new Vue({
            el: '#inFo_Supports',
            data() {
                return {
                    data: {
                        val: [{}],
                        templates: []
                    },
                    isShow: true,
                    objDefault: [{}]
                }
            },
            mounted(){

            },
            methods: {
                addLine: function () {
                    this.isShow = true;
                    return this.data.val.push({});
                },
                trashLine: function (topic_props,index) {
                    if(this.data.val.length > 1) {
                        this.data.val.splice(index, 1);
                    }else {
                        this.data.val.splice(index, 1);
                        return this.data.val.push();
                    }
                },
            }
        });
        $('#province_id').change(function () {
            var province_id = $('#province_id').val();
            if(province_id) {
                shop.ajax_popup('warehouse/getDistrict', 'POST', {id: province_id}, function(json){
                    var len = 0;
                    $("#district_id").empty();
                    if (json['data'] != null) {
                        len = json['data'].length;
                    }
                    if (len > 0) {
                        $("#district_id").append('<option selected disabled>Quận/Huyện</option>');
                        for (var i = 0; i < len; i++) {
                            var name = json['data'][i].Name_VI;
                            var id = json['data'][i].id;
                            $("#district_id").append('<option  value="' + id + '">' + name + '</option>');
                        }
                    } else {
                        $("#district_id").empty();
                    }

                });

            }else{
                $("#district_id").empty();
            }
        })

    </script>
@stop