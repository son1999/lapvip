<div  id="product-admin-prices">
    <div v-for="cate in filter_cate_price" class="mb-3">
        <h6 class="mb-3 col-12">@{{cate.title}}</h6>
        <div class="d-flex flex-wrap">
            <div class="radio radio-info mb-2 col-2">
                <input type="radio" v-bind:id="'filter_0_cate'+cate.id" checked v-model="cate.checked" v-bind:name="'filter_cate_[' + cate.id+']'" v-bind:value="0">
                <label v-bind:for="'filter_0_cate'+cate.id">
                    Không chọn
                </label>
            </div>
            <div class="radio radio-info mb-2 col-2" v-for="filter in cate.filters">
                <input type="radio" v-bind:id="'filter_' + filter.id" v-model="cate.checked" v-bind:name="'filter_cate_[' + cate.id+']'" v-bind:value="filter.id">
                <label v-bind:for="'filter_' + filter.id" v-if="filter.title.indexOf('#') == -1">
                    <div class="image_filter_price mt-0">
                        <img v-bind:src="'{{asset('upload/filters/original')}}/'+filter.image" />
                    </div>
                </label>
                <label v-bind:for="'filter_' + filter.id" class="filter-title" v-if="filter.title.indexOf('#') != -1" >
                    <div class="image_filter_price mt-0">
                        <span class="rounded-circle" v-bind:style="{background: filter.title, height: 30 +'px', width: 30 +'px', display: 'block' }"></span>
                    </div>
                </label>
{{--                <input type="radio" v-bind:id="'filter_' + filter.id" v-model="cate.checked" v-bind:name="'filter_cate_[' + cate.id+']'" v-bind:value="filter.id">--}}
{{--                <label v-bind:for="'filter_' + filter.id" class="filter-title" v-if="filter.title.indexOf('#') == -1">@{{ filter.title }}</label>--}}
{{--                <label v-bind:for="'filter_' + filter.id" class="filter-title" v-if="filter.title.indexOf('#') != -1">--}}
{{--                    <span class="rounded-circle" v-bind:style="{background: filter.title, height: 20 +'px', width: 20 +'px', display: 'block' }"></span>--}}
{{--                </label>--}}
            </div>
        </div>
    </div>
    <div class="col-12">
        <button class="btn btn-outline-info" @click="filter_click($event)">Chọn</button>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card-box" >
                <h4 class="header-title mb-4">Danh sách giá và số lượng</h4>

                <ul class="nav nav-tabs tab-price">
                    <li class="nav-item" v-if="filter_cate_price.length > 0" v-for="(item, index_price ) in filter_prices">
                        <a v-bind:href="'#price-'+index_price" data-toggle="tab" aria-expanded="true" class="nav-link" >
                            <input type="hidden" name="filter_price_ids[]" v-bind:value="item.key_price">
                            <span v-for="abc in item.obj" v-if="abc.title.indexOf('#') != -1" v-bind:style="{background: abc.title, height: 30 +'px', width: 50 +'px', display: 'block' }"> </span>
                            <span v-for="abc in item.obj" v-if="abc.title.indexOf('#') == -1" v-bind:style="{height: 30 +'px',  display: 'block' }"> @{{ abc.title }} </span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content position-relative tab-price">
                    <div class="tab-pane row" v-for="(item, index_price ) in filter_prices" v-bind:id="'price-'+index_price">
                        <div class="col-sm-12 d-flex flex-wrap">
                            <div class="col-sm-4 form-group mr-3 px-0 ">
                                <div class="form-group">
                                    <label for="price">Giá hiển thị</label>
                                    <input v-if="item.price != undefined" type="text" class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" id="price" name="prices[]" v-bind:value="item.price" required onkeypress="return shop.numberOnly()" onkeyup="mixMoney(this)" onfocus="this.select()">
                                    <input v-else type="text" class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" id="price" name="prices[]" v-bind:value="item.base_price"  required onkeypress="return shop.numberOnly()" onkeyup="mixMoney(this)" onfocus="this.select()">
                                </div>
                            </div>
                            <div class="col-sm-4 form-group mr-3 px-0 ">
                                <div class="form-group">
                                    <label for="priceStrike">Giá gạch</label>
                                    <input type="text" v-if="item.price_strike != undefined" class="form-control{{ $errors->has('priceStrike') ? ' is-invalid' : '' }}" id="priceStrike[]" name="priceStrikes[]" v-bind:value="item.price_strike" onkeypress="return shop.numberOnly()" onkeyup="mixMoney(this)"  onfocus="this.select()">
                                    <input type="text" v-else class="form-control{{ $errors->has('priceStrike') ? ' is-invalid' : '' }}" id="priceStrike[]" name="priceStrikes[]" v-bind:value="item.base_priceStrike" onkeypress="return shop.numberOnly()" onkeyup="mixMoney(this)"  onfocus="this.select()">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 d-flex flex-wrap">
                            <div class="form-group mr-3 px-0 col-md-4" v-for="wh in item.warehouse">
                                <div>
                                    <label for="unit">@{{wh.title}}</label>
                                    <input v-if="wh.amount != undefined" v-bind:value="wh.amount" type="text" class="form-control" id="quantity" v-bind:name="'quantity['+index_price+']['+wh.id+']'">
                                    <input v-else v-bind:value="100" type="text" class="form-control" id="quantity" v-bind:name="'quantity['+index_price+']['+wh.id+']'">
                                </div>
                            </div>
                        </div>
                        <div class="form-group position-absolute" style="top: 10px; right: 10px; height: calc(1.5em + .9rem + 2px);"><button class="btn btn-danger" @click="remove_price($event, item);">Xóa</button></div>
                    </div>
                </div>
            </div> <!-- end card-box-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->
    @include('BackEnd::layouts.loader')
</div>

@push('js_bot_all')
    <script>
        {{--var mount = '{!! json_encode($mount) !!}';--}}
        var warehouse_ = '{!! json_encode($warehouse) !!}';
        var filter_cate_price = '{!! json_encode($filter_cate_price) !!}';
        var filter_prices = '{!! isset($product_prices['data']) ? json_encode($product_prices['data']) : '' !!}';
    </script>
    {!! \Lib::addMedia('admin/features/product/product_prices.js') !!}
@endpush