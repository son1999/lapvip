@extends('BackEnd::layouts.default')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            @if(old_blade('editMode'))
                {!! Form::open(['url' => route('admin.'.$key.'.edit.post', old_blade('id')) , 'files' => true]) !!}
            @else
                {!! Form::open(['url' => route('admin.'.$key.'.add.post') , 'files' => true]) !!}
            @endif
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

            <div class="card">
                <div class="card-header">
                    <i class="fe-menu-square-o"></i>THÔNG TIN KỊCH BẢN TRẢ GÓP
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="title">Tiêu đề</label>
                            <input type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" value="{{ old_blade('title') }}" >
                        </div>
                        <div class="col-sm-6">
                            <label for="month">Tháng trả góp</label>
                            <input type="text" class="form-control {{ $errors->has('month') ? ' is-invalid' : '' }}" id="month" name="month" value="{{old_blade('month')}}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" id="product_have_sale">
                <div class="card-header">
                    <i class="fe-menu-square-o"></i>KỊCH BẢN TRẢ GÓP CÔNG TY TÀI CHÍNH
                </div>
                <div class="card-body">
                    <div class="row" >
                        <div class="col-12" >
                            <div class="fa-border mt-3" v-for="(topic, key_topic) in data.val">
                                <div class="row">
                                    <div class="col-12 d-flex">
                                        <label for="" class="col-1 p-1">Company</label>
                                        <input class="col-md-5 p-1 form-control mr-4" type="text" v-bind:name="'company[]'"  placeholder="Công ty tài chính" autocomplete="off" v-model="topic.company" required>
                                        <label for="" class="col-1 p-1">Hình ảnh</label>
                                        <input class="col-md-2 p-1 form-control" type="file" v-bind:name="'image_scenarios[]'" >
                                        <input class="col-md-3 p-1 form-control" type="hidden" v-bind:name="'name_image[]'"  v-model="topic.image">
                                        <div  class="ml-3 mr-3" v-if="topic.image != undefined">
                                            <img :src="'{{asset('upload/original/')}}/' + topic.image" alt="">
                                        </div>
                                        <span @click="addTopic" class="btn btn-success ml-2 mb-1"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                        <span @click="trashTopic(key_topic)" v-if="isShow" class="btn btn-danger ml-2 mb-1"><i class="fas fa-trash"></i></span>
                                    </div>
                                    <div class="col-12 d-flex mt-2">
                                        <label for="" class="col-1 p-1">Phụ phí</label>
                                        <input class="col-md-5 p-1 form-control mr-4" type="text" min="1000" v-bind:name="'surcharge[]'"  placeholder="" autocomplete="off" v-model="topic.surcharge" required>
                                        <label for="" class="col-1 p-1">Description</label>
                                        <input class="col-md-4 p-1 form-control mr-4" type="text" v-bind:name="'des[]'"  placeholder="" autocomplete="off" v-model="topic.des" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 offset-0 mt-2">
                                        <div class="row d-flex">
                                            <div class="col-11">
                                                <div class="col-11 offset-1 d-flex mt-2">
                                                    <div class="d-flex col-10">
                                                        <label for="" class="col-2 p-1">% Trả trước</label>
                                                        <input class="col-md-4 p-1 form-control mr-4" type="text" v-bind:name="'prepay[]'"  placeholder="% Trả trước" autocomplete="off" v-model="topic.prepay" required>
                                                    </div>
                                                </div>
                                                <div class="col-11 offset-1 d-flex mt-2">
                                                    <div class="d-flex col-10">
                                                        <label for="" class="col-2 p-1">% Theo tháng</label>
                                                        <input class="col-md-4 p-1 form-control mr-4" type="text" v-bind:name="'per_pay_mo[]'"  placeholder="% Theo tháng" autocomplete="off" v-model="topic.per_pay_mo" required>
                                                    </div>
                                                </div>
                                                <div class="col-11 offset-1 d-flex mt-2">
                                                    <div class="d-flex col-10" >
                                                        <label for="" class="col-2 p-1">Giấy tờ bắt buộc</label>
                                                        <div class="col-12 p-0" >
                                                            <div class="d-flex mb-2" v-for="(pager, key) in topic.pagers_required">
                                                                <input v-if="pager.length > 0" class="col-md-4 p-1 form-control mr-4" type="text" v-bind:name="'pagers_'+key_topic+'[]'"  placeholder="" autocomplete="off" :value="pager" required>
                                                                <input v-else class="col-md-4 p-1 form-control mr-4" type="text" v-bind:name="'pagers_'+key_topic+'[]'"  placeholder="" autocomplete="off" value="" required>
                                                                <span @click="addLine(topic)" class="btn btn-success ml-2 mb-1"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                                                <span @click="trashLine(topic.pagers_required,key)" v-if="isShow" class="btn btn-danger ml-2 mb-1"><i class="fe-trash-2" aria-hidden="true"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->
                </div>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Cập nhật</button>
                &nbsp;&nbsp;
                <a class="btn btn-sm btn-danger" href="{{ redirect()->back()->getTargetUrl() }}"><i class="fa fa-ban"></i> Hủy bỏ</a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop
@section('js_bot')
    <script type="text/javascript">
        function mixMoney(myfield) {
            var thousands_sep = '.',
                val = parseInt(myfield.value.replace(/[.*+?^${}()|[\]\\]/g, ''));
            myfield.value = shop.numberFormat(val, 0, '', thousands_sep);
        }
        var promote = '{!! isset($data->installment_scenarios->properties) ? $data->installment_scenarios->properties : '' !!}';
        var pro = new Vue({
            el: '#product_have_sale',
            data() {
                return {
                    data: {
                        val: promote != '' ? JSON.parse(promote) : [{company:'', image:'', surcharge:'', des:'', prepay: '', per_pay_mo:'',pagers_required:[{}]}],
                        templates: []
                    },
                    isShow: true,
                    objDefault: [{company:'', image:'', surcharge:'', des:'', prepay: '', per_pay_mo:'',pagers_required:[{}]}]
                }
            },
            methods: {
                addLine: function (topic) {
                    this.isShow = true;
                    var pagers_required = {pagers_required:[{}]};
                    return topic.pagers_required.push(pagers_required);
                },
                addTopic: function () {
                    this.isShow = true;
                    var abg = {company:'', image:'', surcharge:'', des:'', prepay: '', per_pay_mo:'',pagers_required:[{}]};
                    return this.data.val.push(abg);
                },
                trashLine: function (topic_props,index) {
                    if(topic_props.length > 1) {
                        topic_props.splice(index, 1);
                    }else {
                        topic_props.splice(index, 1);
                        var pagers_required = {pagers_required:[{}]};
                        return topic_props.push(pagers_required);
                    }
                },
                trashTopic: function (index) {
                    if(this.data.val.length > 1) {
                        this.data.val.splice(index, 1);
                    }else {
                        this.data.val.splice(index, 1);
                        var abg = {company:'', image:'', surcharge:'', des:'', prepay: '', per_pay_mo:'',pagers_required:[{}]} ;
                        return this.data.val.push(abg);
                    }
                },

            }
        });
    </script>
@endsection
