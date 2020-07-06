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

            <div class="card" >
                <div class="card-header">
                    <i class="fe-menu-square-o"></i>THÔNG TIN NGÂN HÀNG HỖ TRỢ TRẢ GÓP
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="title">Tiêu đề</label>
                            <input type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" value="{{ old_blade('title') }}" >
                        </div>
                        <div class="col-sm-3">
                            <label for="month">Phụ phí</label>
                            <input type="text" class="form-control {{ $errors->has('surcharge') ? ' is-invalid' : '' }}" id="surcharge" name="surcharge" min="1000" value="{{old_blade('surcharge') ? \Lib::numberFormat(old_blade('surcharge')) : 0 }}" required onkeypress="return shop.numberOnly()" onkeyup="mixMoney(this)" onfocus="this.select()">
                        </div>
                        <div class="col-sm-3">
                            <label for="company">Image Bank</label>
                            <input type="file" class="form-control {{ $errors->has('image') ? ' is-invalid' : '' }}" id="image" name="image" value="{{old_blade('image')}}">
                            <div class="mt-2">
                                @if(!empty(@$data->image))
                                    <div class="pull-right">
                                        <img src="{{ $data->getImageUrl('small') }}" />
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card" id="product_have_sale">
                <div class="card-header">
                    <i class="fe-menu-square-o"></i>KỊCH BẢN TRẢ GÓP NGÂN HÀNG
                </div>
                <div class="card-body">
                    <div class="row" >
                        <div class="col-12" >
                            <div class="fa-border mt-3" v-for="(topic, key_topic) in data.val">
                                <div class="row">
                                    <div class="col-12 d-flex">
                                        <label for="" class="col-1 p-1">Payment</label>
                                        <input class="col-md-5 p-1 form-control mr-4" type="text" v-bind:name="'payment_title[]'"  placeholder="Visa, Mastercard" autocomplete="off" v-model="topic.payment_title">
                                        <input class="col-md-3 p-1 form-control" type="file" v-bind:name="'payment_image[]'" >
                                        <input class="col-md-3 p-1 form-control" type="hidden" v-bind:name="'name_image[]'"  v-model="topic.payment_image">
                                        <div  class="ml-3 mr-3" v-if="topic.payment_image != undefined">
                                            <img :src="'{{asset('upload/original/')}}/' + topic.payment_image" alt="">
                                        </div>
                                        <span @click="addTopic" class="btn btn-success ml-2 mb-1"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                        <span @click="trashTopic(key_topic)" v-if="isShow" class="btn btn-danger ml-2 mb-1"><i class="fas fa-trash"></i></span>

                                    </div>
                                    <div class="col-11 offset-1 mt-2" v-for="(item, key) in topic.month">
                                        <div class="row" >
                                            <div class="col-12 d-flex">
                                                <label for="" class="col-1 p-1">Thời hạn</label>
                                                <input class="col-md-2 p-1 form-control mr-4" type="text" v-bind:name="'month_'+key_topic+'[]'"  placeholder="Số tháng trả góp VD: 6" autocomplete="off" v-model="item.month">
                                                <span @click="addLine(topic)" class="btn btn-success ml-2 mb-1"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                                <span @click="trashLine(topic.month,key)" v-if="isShow" class="btn btn-danger ml-2 mb-1"><i class="fas fa-trash"></i></span>
                                            </div>
                                            <div class="col-11" v-for="props in item.props">
                                                <div class="col-11 offset-1 d-flex mt-2">
                                                    <div class="d-flex col-10">
                                                        <label for="" class="col-2 p-1">Lãi suất</label>
                                                        <input class="col-md-4 p-1 form-control mr-4" type="text" v-bind:name="'interest_rate_'+key_topic+'-'+key"  placeholder="% Lãi suất theo tháng" autocomplete="off" v-model="props.interest_rate">
                                                    </div>
                                                </div>
                                                <div class="col-11 offset-1 d-flex mt-2">
                                                    <div class="d-flex col-10">
                                                        <label for="" class="col-2 p-1">Phí chuyển đổi</label>
                                                        <input class="col-md-4 p-1 form-control mr-4" type="text" v-bind:name="'conversion_fee_'+key_topic+'-'+key"  placeholder="% phí chuyển đổi theo tháng" autocomplete="off" v-model="props.conversion_fee">
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
        var promote = '{!! isset($data->installment_payment_bank->properties) ? $data->installment_payment_bank->properties : '' !!}';
        var pro = new Vue({
            el: '#product_have_sale',
            data() {
                return {
                    data: {
                        val: promote != '' ? JSON.parse(promote) : [{payment_title:'',payment_image:'' ,month:[{month: '', props:[{}]}]}],
                        templates: []
                    },
                    isShow: true,
                    objDefault: [{payment_title:'',payment_image:'' ,month:[{month: '', props:[{}]}]}]
                }
            },
            methods: {
                addLine: function (topic) {
                    this.isShow = true;
                    var month = {month: '', props:[{}]};
                    return topic.month.push(month);
                },
                addTopic: function () {
                    this.isShow = true;
                    var abg = {payment_title:'',payment_image:'' ,month:[{month: '', props:[{}]}]};
                    return this.data.val.push(abg);
                },
                trashLine: function (topic_props,index) {
                    if(topic_props.length > 1) {
                        topic_props.splice(index, 1);
                    }else {
                        topic_props.splice(index, 1);
                        var month = {month: '', props:[{}]};
                        return topic_props.push(month);
                    }
                },
                trashTopic: function (index) {
                    if(this.data.val.length > 1) {
                        this.data.val.splice(index, 1);
                    }else {
                        this.data.val.splice(index, 1);
                        var abg = {payment_title:'',payment_image:'' ,month:[{month: '', props:[{}]}]} ;
                        return this.data.val.push(abg);
                    }
                },

            }
        });

        {{--var promote = '{!! isset($data->detail->promote_props) && $data->detail-> promote_props ? $data->detail->promote_props : '' !!}';--}}
        {{--var pro = new Vue({--}}
        {{--    el: '#product_have_sale',--}}
        {{--    data() {--}}
        {{--        return {--}}
        {{--            data: {--}}
        {{--                val: promote != '' ? JSON.parse(promote) : [{title:'',props:[{}]}],--}}
        {{--                templates: []--}}
        {{--            },--}}
        {{--            isShow: true,--}}
        {{--            objDefault: [{title:'aaa',props:[{}]}]--}}
        {{--        }--}}
        {{--    },--}}
        {{--    methods: {--}}
        {{--        addLine: function (topic) {--}}
        {{--            this.isShow = true;--}}
        {{--            return topic.props.push({});--}}
        {{--        },--}}
        {{--        addTopic: function () {--}}
        {{--            this.isShow = true;--}}
        {{--            var abg = {title:'',props:[{}]};--}}
        {{--            return this.data.val.push(abg);--}}
        {{--        },--}}
        {{--        trashLine: function (topic_props,index) {--}}
        {{--            if(topic_props.length > 1) {--}}
        {{--                topic_props.splice(index, 1);--}}
        {{--            }else {--}}
        {{--                topic_props.splice(index, 1);--}}
        {{--                return topic_props.push({});--}}
        {{--            }--}}
        {{--        },--}}
        {{--        trashTopic: function (index) {--}}
        {{--            if(this.data.val.length > 1) {--}}
        {{--                this.data.val.splice(index, 1);--}}
        {{--            }else {--}}
        {{--                this.data.val.splice(index, 1);--}}
        {{--                var abg = {title:'',props:[{}]};--}}
        {{--                return this.data.val.push(abg);--}}
        {{--            }--}}
        {{--        },--}}

        {{--    }--}}
        {{--});--}}
    </script>
@endsection
