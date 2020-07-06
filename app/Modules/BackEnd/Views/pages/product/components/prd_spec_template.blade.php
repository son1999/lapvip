<div id="prdProperties">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="lang">Chọn mẫu thông số sản phẩm <span class="text-danger">*</span></label>
                <select @change="getPropsOfTemplate($event)" class="form-control" >
                    {{--@foreach($langOpt as $k => $v)--}}
                        {{--<option value="{{ $k }}"{{ old_blade('lang') == $k ? ' selected="selected"' : '' }}>{{ $v }}</option>--}}
                    {{--@endforeach--}}
                    <option value="">--Chọn mẫu--</option>
                    <option v-bind:value="temp.id" value="" v-for="(temp,index) in data.templates">@{{ temp.title }}</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12" >
            <div class="fa-border mt-3" v-for="(topic, key_topic) in data.val">
                <div class="row">
                    <div class="col-6">
                        <label for="">Chủ đề</label>
                        <input class="col-6 p-1" type="text" name="prop_topics[]" v-model="topic.title"/>
{{--                        <span @click="addTopic" class="btn btn-success ml-2 mb-1"><i class="fa fa-plus" aria-hidden="true"></i></span>--}}
{{--                        <span @click="trashTopic(key_topic)" v-if="isShow" class="btn btn-danger ml-2 mb-1"><i class="fas fa-trash"></i></span>--}}
                    </div>
                </div>

                <div class="row" v-for="(item, key) in topic.props">
                    <div class="col-12 m-3">
                        <label for="">Thuộc tính</label>
                        <input class="col-md-3 p-1" type="text" v-bind:name="'property_titles_'+key_topic+'[]'" v-model="item.title">
                        <label for="">Nội dung</label>
                        <input class="col-md-3 p-1" type="text" v-bind:name="'property_values_'+key_topic+'[]'" v-model="item.value">
{{--                        <span @click="addLine(topic)" class="btn btn-success ml-2 mb-1"><i class="fa fa-plus" aria-hidden="true"></i></span>--}}
{{--                        <span @click="trashLine(topic.props,key)" v-if="isShow" class="btn btn-danger ml-2 mb-1"><i class="fas fa-trash"></i></span>--}}
                    </div>
                </div>
            </div>
        </div><!-- end col -->
    </div>
</div>

@push('js_bot_all')
    <script type="text/javascript">
        var properties = '{!! isset($data->detail->properties) && $data->detail->properties ? $data->detail->properties : '' !!}';
        var prdProperties = new Vue({
            el: '#prdProperties',
            data() {
                return {
                    data: {
                        val: properties != '' ? JSON.parse(properties) : [{title:'',props:[{}]}],
                        templates: []
                    },
                    isShow: true,
                    objDefault: [{title:'aaa',props:[{}]}]
                }
            },
            mounted(){
                this.getSpecsTemplates();
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
                },
                getSpecsTemplates: function() {
                    $.ajax({
                        type: 'POST',
                        url: "/admin/ajax/product/get-all-specs-temp",
                        data: {
                            _token:ENV.token
                        },
                        dataType: 'json',
                    }).done(function(json) {
                        prdProperties.data.templates = json.data;
                    });
                },
                getPropsOfTemplate: function(e) {
                    var id = e.target.value;
                    $.ajax({
                        type: 'POST',
                        url: "/admin/ajax/product/get-specs-temp-by-id",
                        data: {
                            _token:ENV.token,
                            id:id
                        },
                        dataType: 'json',
                    }).done(function(json) {
                        prdProperties.data.val = JSON.parse(json.data.properties)
                    });
                },
            }
        });
        // const anElement = new AutoNumeric('#autonumberic');
    </script>
@endpush