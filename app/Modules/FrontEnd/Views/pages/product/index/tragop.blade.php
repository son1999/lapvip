    @extends('FrontEnd::layouts.home')
@section('title') {!! \Lib::siteTitle($site_title, $def['site_title'],false,true) !!} @stop

@section('content')
    <div class="container" id="app_prd_category">
        {!! \Lib::renderBreadcrumb(false, true,'FrontEnd::layouts.breadcrumb') !!}
        <div class="filter">
            <div class="row">
                <div class="col-md-3 has-padding-none">
                    <div class="sidebar-left-filter-wrap slidebar-goi-tra-gop mb-4">
                        <div class="title">chọn gói trả góp</div>
                        <ul>
                            @foreach($installment as $cat_ins)
                                <li class="goi-tra-gop-item">
                                    <p>
                                        <a href="{{ route('product.list', ['alias'=> request()->alias ? request()->alias : $cat_ins['title'], 'parent_id' => request()->parent_id, 'id' => request()->id, 'ins'=>$cat_ins['id'] ]) }}">
                                            <img data-src="{{asset('upload/category/original/'.$cat_ins['icon'])}}" class="lazyload">
                                            <span class="title-tra-gop">{{$cat_ins['title']}}</span>
                                        </a>
                                    </p>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                    <div class="sidebar-left-filter-wrap sidebar-guild mb-4">
                        @foreach($menu_footer as $i_m_f )
                            @if($i_m_f['cat_id_footer'] == request()->parent_id)
                                <div class="title">{{$i_m_f['title']}}</div>
                                <ul>
                                    @if(!empty($i_m_f['sub']))
                                        @foreach($i_m_f['sub'] as $i_m_f_s)
                                            <li><a href="{{route('trangtinh', ['link_seo' => \Illuminate\Support\Str::slug($i_m_f_s['title'])])}}">{{$i_m_f_s['title']}}</a></li>
                                        @endforeach
                                    @endif
                                </ul>
                            @endif
                        @endforeach
                    </div>
                    <div class="sidebar-left-filter-wrap">
                        <div class="title">bộ lọc</div>
                        <div id="accordion" role="tablist">
                            <div class="card manufacturer" v-for="(cate, index) in filter.filter_cate" v-if="cate.show_filter != 1">
                                <div v-if="cate.show_filter_mobile != 1">
                                    <div class="card-header" role="tab" id="headingOne">
                                        <h5 class="mb-0">
                                            <a v-if="cate.haveCheck == 1" data-toggle="collapse" :href="'#collapseOne'+index" aria-expanded="true" aria-controls="collapseOne" > @{{ cate.title }}</a>
                                            <a v-else data-toggle="collapse" :href="'#collapseOne'+index" aria-expanded="false" aria-controls="collapseOne" class="collapsed" > @{{ cate.title }}</a>
                                        </h5>
                                    </div>
                                    <div v-if="cate.haveCheck == 1" :id="'collapseOne'+index" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                                        <div class="card-body has-scroll">
                                            <div class="inner">
                                                <label>
                                                    <input type="checkbox" @click="checkAll($event,cate)"/>Tất cả
                                                    <span class="checkmark"></span>
                                                </label>
                                                <label v-for="filter in cate.filters">
                                                    <input type="checkbox" v-if="filter.checked == 1" checked @click="remove_filter_checkbox($event,filter)"/>
                                                    <input type="checkbox" v-else @click="choose_filters($event,filter,cate)"/>
                                                    <span  v-if="filter.title.indexOf('#') == -1">@{{ filter.title }}</span>
                                                    <span class="dumeno" v-if="filter.title.indexOf('#') != -1" v-bind:style="{backgroundColor: filter.title}"></span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else :id="'collapseOne'+index" class="collapse" role="tabpanel" aria-labelledby="headingOne">
                                        <div class="card-body has-scroll">
                                            <div class="inner">
                                                <label>
                                                    <input type="checkbox" checked="checked" />Tất cả
                                                    <span class="checkmark"></span>
                                                </label>
                                                <label v-for="filter in cate.filters">
                                                    <input type="checkbox" v-if="filter.checked == 1" checked @click="remove_filter_checkbox($event,filter)"/>
                                                    <input type="checkbox" v-else @click="choose_filters($event,filter,cate)"/>
                                                    <span  v-if="filter.title.indexOf('#') == -1">@{{ filter.title }}</span>
                                                    <span class="dumeno" v-if="filter.title.indexOf('#') != -1" v-bind:style="{backgroundColor: filter.title}"></span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="list-tab-cat">
                        @foreach($category as $item_cat)
                            @if($item_cat['show_installment_page'] == 1)
                                <a href="{{ route('product.list', ['alias'=> \Illuminate\Support\Str::slug($item_cat['title']), 'parent_id' => request()->parent_id, 'id'=>$item_cat['id']]) }}@if(!empty(request()->ins))&ins={{request()->ins}}@endif" @if(request()->id == $item_cat['id']) class="active" @endif>{{$item_cat['title']}}</a>
                            @endif
                        @endforeach
                    </div>
                    <div class="filter-product-right">
                        <div class="filter-head">
                            <div class="fitler-head-title">
                                <span></span>
                            </div>
                            <div class="filter-sort">
                                <button type="button"> Sắp xếp theo <span class="sortText" v-for="(item, index) in filter.sort_by" v-if="index == {{!empty(request()->sort_by) ? request()->sort_by : 0}}">@{{ item }}</span></button>
                                <div class="has-dropdowm">
                                    <ul class="list-unstyled" >
                                        <li v-for="(item, index) in filter.sort_by">
                                            <a class="has-icon sortdefault "  @click="pick_sort_by($event,index)">@{{ item }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="filter-box" v-if="filter.choosed_filters.length > 0">
                            <h5>Lọc theo :</h5>
                            <div class="filter-value" v-for="choosed_filter in filter.choosed_filters">
                                @{{ choosed_filter.cate_title }}: @{{ choosed_filter.filter_title }}
                                <img @click="remove_filter($event,choosed_filter)" data-src="{{asset('html-viettech/images/icon-close-white.png')}}" class="lazyload" alt />
                            </div>
                            <div class="delete">
                                Xóa tất cả
                                <a href="javascript:;" v-if="filter.choosed_filters.length > 0" @click="remove_all_filter()"><img data-src="{{asset('html-viettech/images/icon-close-white.png')}}" class="lazyload" alt /></a>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0 p-list">
                        @if(count($data) > 0)
                            @foreach($data as $item_pro)
                                <div class="col-6 col-lg-3 p-0 mb-1">
                                    <div class="product-item-2">
                                        <a href="{{route('product.detail', ['alias' => $item_pro->alias])}}" class="wrap-img">
                                            <img data-src="{{\ImageURL::getImageUrl($item_pro->image, \App\Models\Product::KEY, 'original')}}" class="lazyload" alt="">
                                        </a>
                                        <div class="body">
                                            <a href="{{route('product.detail', ['alias' => $item_pro->alias])}}" class="name"> {{$item_pro->title}}</a>
                                            @if($item_pro->out_of_stock == 0)
                                                @if($item_pro->priceStrike > 0)
                                                    <span class="price">
                                                        <span class="new text-danger">{{\Lib::priceFormatEdit($item_pro->price)['price']}} đ </span>
                                                    </span>
                                                    <span class="price">
                                                        <span class="old">{{\Lib::priceFormatEdit($item_pro->priceStrike, '')['price']}} đ </span>
                                                    </span>
                                                @else
                                                    <span class="price">
                                                        <span class="new text-danger">{{\Lib::priceFormatEdit($item_pro->price, '')['price']}} đ </span>
                                                    </span>
                                                    <span class="price d-flex">
                                                        <span class="old"></span>
                                                    </span>
                                                @endif
                                            @else
                                                <span class="price">
                                                    <span class="new text-danger">Liên hệ</span>
                                                </span>
                                                <span class="price d-flex">
                                                    <span class="old"></span>
                                                </span>
                                            @endif
                                            <div class="stars">
                                                <span class="vote"><span class="star" data-vote="{{$item_pro->rate_avg != 0 ? $item_pro->rate_avg : 0}}"></span></span>
                                            </div>
                                            <div class="des">
                                                @foreach(explode('|', $item_pro->parameter) as $key => $parameter_product)
                                                    @if($key < 5)
                                                        @php($str = substr( $parameter_product, 0, strpos( $parameter_product, ":" )))
                                                        <div class="prameter-filter">
                                                            <b>{{$str}}</b>{{str_replace($str,'',$parameter_product)}}
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="w-100 bg-light">
                                <img class="rounded mx-auto d-block mt-5 lazyload" data-src="{{asset('images/noti-search.png')}}" alt="">
                                <p class="name text-center w-100">Rất tiếc chúng tôi không tìm thấy kết quả theo yêu cầu của bạn. Vui lòng thử lại .</p>
                            </div>
                        @endif
                        @if ($data->total() > 12)
                            <div class="col-12 m-0 mb-2">
                                <div class="bg-white row py-5 justify-content-center mt-5">
                                    <nav aria-label="Page navigation" class="main-wrap">
                                        {{$data->links('FrontEnd::layouts.pagin')}}
                                    </nav>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-12">
                    <div id="faq" class="main-wrap faq">
                        <div class="title heading">Hỏi và Đáp</div>
                        <form action="{{route('installment.question')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="name"  placeholder="Họ và Tên" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="email" class="form-control" name="email"  placeholder="Email" required>
                                    </div>
                                </div>
                                <textarea placeholder="Viết bình luận của bạn( Vui lòng gõ tiếng Việt có dấu) " class="form-control" rows="5" name="question" style="overflow:auto" required></textarea>
                            </div>
                            <div class="send text-right">
                                <button type="submit">Gửi câu hỏi</button>
                            </div>
                        </form>
                        @foreach ($comment['question'] as $key => $item)
                            {{--                        @php($count_item = count($comment['question']))--}}
                            <div class="media">
                                <img class="mr-3 lazyload" data-src="{{asset('html-viettech/images/icon-person.png')}}" alt="Generic placeholder image" />
                                <div class="media-body">
                                    <h5 class="mt-0">
                                        {{$item['name']}} <small>{{\Lib::dateFormat($item->created)}}</small>
                                    </h5>
                                    <span>{{$item['question']}}</span>
                                    <div class="reply js-show-reply">
                                        <a href="javascript:void(0)">Trả lời</a>
                                    </div>
                                    @foreach($comment['answer_ques'] as $item_as)
                                        @if($item_as['qid'] != 0 && $item_as['qid'] == $item['id'])
                                            @if(!empty($item_as['answer']) && !empty($item_as['aid']))
                                                <div class="media mt-3">
                                                    <a class="pr-3" href="#">
                                                        <img data-src="{{asset('html-viettech/images/icon-person.png')}}" class="lazyload" alt="Generic placeholder image" />
                                                    </a>
                                                    <div class="media-body">
                                                        <h5 class="mt-0">
                                                            {{$item_as['name']}}
                                                            <span class="admin">QTV</span>
                                                            <span class="time">{{\Lib::dateFormat($item_as->created)}}</span>
                                                        </h5>
                                                        <p>{{$item_as['answer']}}</p>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="media mt-3">
                                                    <a class="pr-3" href="#">
                                                        <img data-src="{{asset('html-viettech/images/icon-person.png')}}" class="lazyload" alt="Generic placeholder image" />
                                                    </a>
                                                    <div class="media-body">
                                                        <h5 class="mt-0">
                                                            {{$item_as['name']}}
                                                            <small>{{\Lib::dateFormat($item_as->created)}}</small>
                                                        </h5>
                                                        <p>{{$item_as['question']}}</p>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                    <div class="box-reply js-box-reply" style="display: none">
                                        <form action="{{route('installment.question', ['qid' => $item->id])}}" method="POST">
                                            @csrf
                                            <div class="row mb-3">
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" name="name"  placeholder="Họ và Tên" required>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                                                </div>
                                            </div>
                                            <textarea rows="3" class="box-ad-comment" name="question" placeholder="Viết bình luận của bạn (Vui lòng gõ tiếng Việt có dấu)" required></textarea>
                                            <div class="fs-cmbtn-send text-right">
                                                <button class="btn_comment_send_sub" type="submit">Gửi câu hỏi</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mx-0">
                        @if ($comment['paginate']->total() > 1)
                            <nav aria-label="Page navigation" class="main-wrap">
                                {{$comment['paginate']->render('FrontEnd::layouts.pagin')}}
                            </nav>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bhx-main container"></div>
@endsection

@push('js_bot_all')
    <script>
        $(window).bind('load', function(){
            $('.js-show-reply a').click(function() {
                $(this).parent().siblings('.js-box-reply').slideToggle();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $(this).parent().siblings('.js-box-reply').offset().top
                }, 300);
            });
        });


        var sort_by = '{!! json_encode($orderClauseText) !!}';
        var filter_cate = '{!! json_encode($filter_cates) !!}';
        var choosed_filters = '{!! json_encode($choosed_filters) !!}';
    </script>
    {!! \Lib::addMedia('js/features/product/category.js') !!}
@endpush