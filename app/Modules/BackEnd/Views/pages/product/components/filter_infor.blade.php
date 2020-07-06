<div id="product-admin-filters">
    <div v-for="cate in filter_cate_not_price">
        <h5>@{{cate.title}}</h5>
        <div class="row">
            <div class="col-sm-3" v-for="filter in cate.filters">
                <label v-bind:for="'filter_' + filter.id" v-if="filter.pid == 0">
                    <input type="checkbox" v-bind:id="'filter_' + filter.id" v-bind:checked="filter.checked == 1" v-bind:name="'filters_[]'" v-bind:value="filter.id">
                    @{{ filter.title }}
                </label>
                <div v-if="filter.sub != 0">
                    <div class="row ml-3" v-for="fil_sub in filter.sub">
                        <label v-bind:for="'filter_' + fil_sub.id">
                            <input type="checkbox" v-bind:id="'filter_' + fil_sub.id" v-bind:checked="fil_sub.checked == 1" v-bind:name="'filters_[]'" v-bind:value="fil_sub.id" @change="check($event,fil_sub.pid)">
                            @{{ fil_sub.title }}
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('BackEnd::layouts.loader')
</div>

@push('js_bot_all')
    <script>
        {{--var mount = '{!! json_encode($mount) !!}';--}}
        {{--var storage = '{!! json_encode($storage) !!}';--}}
        var collection = '{!! json_encode($collection) !!}';
        var filter_cate_not_price =  {!!  json_encode($filter_cate_not_price) !!};
    </script>
    {!! \Lib::addMedia('admin/features/product/product_filters.js') !!}
@endpush