{{--<div id="prdCompare">--}}
{{--    <div class="row">--}}
{{--        <div class="col-sm-6">--}}
{{--            <div class="form-group">--}}
{{--                <label for="lang">Chọn mẫu thông số so sánh sản phẩm</label>--}}
{{--                <select @change="getProComOfTemplate($event)" class="form-control" required>--}}
{{--                    <option value="1" id="close" selected>--Chọn mẫu--</option>--}}
{{--                    <option v-bind:value="temp.id" value="" v-for="(temp,index) in data.templates">@{{ temp.title }}</option>--}}
{{--                </select>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="row ">--}}
{{--        <div class="col-12" >--}}
{{--            <div class="fa-border mt-3" v-for="(topic, key_topic) in data.val">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-6">--}}
{{--                        <label for="">Chủ đề</label>--}}
{{--                        <input class="col-6 p-1" type="text" name="com_prop_topics[]"  v-model="topic.title"/>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="row" v-for="(item, key) in topic.props">--}}
{{--                    <div class="col-12 m-3">--}}
{{--                        <label for="">Thuộc tính</label>--}}
{{--                        <input class="col-md-3 p-1" type="text" v-bind:name="'com_property_titles_'+key_topic+'[]'"  v-model="item.title">--}}
{{--                        <label for="">Nội dung</label>--}}
{{--                        <input class="col-md-3 p-1" type="text" v-bind:name="'com_property_values_'+key_topic+'[]'"  v-model="item.value">--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div><!-- end col -->--}}
{{--    </div>--}}
{{--</div>--}}

{{--@push('js_bot_all')--}}
{{--    <script type="text/javascript">--}}
{{--        var procompare = '{!! isset($data->detail->comperties) && $data->detail->comperties ? $data->detail->comperties : '' !!}';--}}
{{--        var prdCompares = new Vue({--}}
{{--            el: '#prdCompare',--}}
{{--            data() {--}}
{{--                return {--}}
{{--                    data: {--}}
{{--                        val: procompare != '' ? JSON.parse(procompare) : [{title:'',props:[{}]}],--}}
{{--                        templates: []--}}
{{--                    },--}}
{{--                    isShow: true,--}}
{{--                    objDefault: [{title:'aaa',props:[{}]}]--}}
{{--                }--}}
{{--            },--}}
{{--            mounted(){--}}
{{--                this.getCompareTemplates();--}}
{{--            },--}}
{{--            methods: {--}}
{{--                getCompareTemplates: function() {--}}
{{--                    $.ajax({--}}
{{--                        type: 'POST',--}}
{{--                        url: "/admin/ajax/product/get-all-compare-temp",--}}
{{--                        data: {--}}
{{--                            _token:ENV.token--}}
{{--                        },--}}
{{--                        dataType: 'json',--}}
{{--                    }).done(function(json) {--}}
{{--                        prdCompares.data.templates = json.data;--}}
{{--                    });--}}
{{--                },--}}
{{--                getProComOfTemplate: function(e) {--}}

{{--                    var id = e.target.value;--}}
{{--                    $.ajax({--}}
{{--                        type: 'POST',--}}
{{--                        url: "/admin/ajax/product/get-com-temp-by-id",--}}
{{--                        data: {--}}
{{--                            _token:ENV.token,--}}
{{--                            id:id--}}
{{--                        },--}}
{{--                        dataType: 'json',--}}
{{--                    }).done(function(json) {--}}
{{--                        prdCompares.data.val = JSON.parse(json.data.properties)--}}

{{--                    });--}}
{{--                },--}}
{{--            }--}}
{{--        });--}}
{{--        // const anElement = new AutoNumeric('#autonumberic');--}}
{{--    </script>--}}
{{--@endpush--}}