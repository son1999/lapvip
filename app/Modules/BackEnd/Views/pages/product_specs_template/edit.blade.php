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
                    <i class="fe-menu-square-o"></i>  SỬA THÔNG TIN
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="title">Tiêu đề</label>
                            <input type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" value="{{ old_blade('title') }}" >
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card" id="addLine">
                        <div class="card-header">
                            <i class="fa fa-newspaper-o"></i>Thông số
                        </div>
                        <div class="card-body">
                            <div class="fa-border mt-3" v-for="(topic, key_topic) in data.val">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="">Chủ đề</label>
                                        <input class="col-6 p-1" type="text" name="prop_topics[]" required v-model="topic.title"/>
                                        <span @click="addTopic" class="btn btn-success ml-2 mb-1"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                        <span @click="trashTopic(key_topic)" v-if="isShow" class="btn btn-danger ml-2 mb-1"><i class="fas fa-trash"></i></span>
                                    </div>
                                </div>

                                <div class="row" v-for="(item, key) in topic.props">
                                    <div class="col-12 m-3">
                                        <label for="">Thuộc tính</label>
                                        <input class="col-md-3 p-1" type="text" v-bind:name="'property_titles_'+key_topic+'[]'" required v-model="item.title">
{{--                                        <label for="">Nội dung</label>--}}
{{--                                        <input class="col-md-3 p-1" type="text" v-bind:name="'property_values_'+key_topic+'[]'"  v-model="item.value">--}}
                                        <span @click="addLine(topic)" class="btn btn-success ml-2 mb-1"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                        <span @click="trashLine(topic.props,key)" v-if="isShow" class="btn btn-danger ml-2 mb-1"><i class="fas fa-trash"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card-->
                </div><!-- end col -->
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
@section('css')
    {!! \Lib::addMedia('admin/js/library/uploadifive/uploadifive.css') !!}
@stop
@section('js_bot')
    {!! \Lib::addMedia('admin/js/library/uploadifive/jquery.uploadifive.min.js') !!}
    {!! \Lib::addMedia('admin/js/library/uploadifive/multiupload.js') !!}
    {!! \Lib::addMedia('admin/js/library/ckeditor/ckeditor.js') !!}
    <script type="text/javascript">
        var properties = '{!! isset($data->properties) && $data->properties ? $data->properties : '' !!}';
        var app = new Vue({
            el: '#addLine',
            data() {
                return {
                    data: {
                        val: properties != '' ? JSON.parse(properties) : [{title:'',props:[{}]}],
                    },
                    isShow: true,
                    objDefault: [{title:'aaa',props:[{}]}]
                }
            },
            methods: {
                addLine: function (topic) {
                    this.isShow = true;
                    return topic.props.push({});
                },
                addTopic: function () {
                    this.isShow = true;
                    var abg = {title:'',props:[{}]};
                    return this.data.val.push(abg);
                },
                trashLine: function (topic_props,index) {
                    if(topic_props.length > 1) {
                        topic_props.splice(index, 1);
                    }else {
                        topic_props.splice(index, 1);
                        return topic_props.push({});
                    }
                },
                trashTopic: function (index) {
                    if(this.data.val.length > 1) {
                        this.data.val.splice(index, 1);
                    }else {
                        this.data.val.splice(index, 1);
                        var abg = {title:'',props:[{}]};
                        return this.data.val.push(abg);
                    }
                }
            }
        });
        // const anElement = new AutoNumeric('#autonumberic');
    </script>
@endsection
