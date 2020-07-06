<div id="prdRelates">
    <div class="row" v-for="(item, key) in data.val">
        <div class="col-12 m-3">
            <label for="">Tiêu đề</label>
            <input class="col-md-5 p-1" type="text" v-bind:name="'relate_titles_[]'"  v-model="item.title">
            <label for="">Id sản phẩm</label>
            <input class="col-md-3 p-1" type="text" v-bind:name="'relate_values_[]'"  onkeypress="return shop.numberOnly()" onfocus="this.select()" v-model="item.id_relate">
            <span @click="addLine" class="btn btn-success ml-2 mb-1"><i class="fa fa-plus" aria-hidden="true"></i></span>
            <span @click="trashLine(data.val, key)" v-if="isShow" class="btn btn-danger ml-2 mb-1"><i class="fas fa-trash"></i></span>
        </div>
    </div>
    <div class="row" v-if="data.val.length > 2">
        <div class="col-sm-12 m-3 d-flex">
            <label for="">Có <span>@{{ data.val.length }}</span></label>
            <input class="col-md-5 p-1 form-control ml-3" type="text" name="option" value="{{old_blade('option')}}" >
        </div>


    </div>
</div>

@push('js_bot_all')
    <script type="text/javascript">
        var relates = '';
        var prdRelates = new Vue({
            el: '#prdRelates',
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
                this.getRelate();
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
                getRelate: function() {
                    var id = {{@$data->id ?? 0}};
                    $.ajax({
                        type: 'POST',
                        url: "/admin/ajax/product/get-all-relates",
                        data: {
                            _token:ENV.token,
                            id:id
                        },
                        dataType: 'json',
                    }).done(function(json) {
                        prdRelates.data.val = json.data.length > 0 ? json.data : [{}];
                    });
                },
            }
        });
        // const anElement = new AutoNumeric('#autonumberic');
    </script>
@endpush