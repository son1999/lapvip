@extends('FrontEnd::layouts.home')
@section('title') {!! \Lib::siteTitle($site_title, $def['site_title'],false,true) !!} @stop

@section('content')
    <div class="container page-accessory-detail">
        {!! \Lib::renderBreadcrumb(false, true,'FrontEnd::layouts.breadcrumb') !!}
        <div class="product-wrapper">
            <section class="product-content">
                <div class="product-content-heading">
                    <div class="product-name">{{$data->title}} <span>(No.{{$data->id}})</span></div>
                </div>
                <div class="row">
                    <div class="col-lg-7">
                        <div class="customize-product-slider custom-accessory">
                            <div class="collection js-carousel" data-items="1" data-dots="true" data-arrows="false" data-autoplay="true"
                                 data-margin="30">
                                <div class="product-slide">
                                    <div class="product-image">
                                        <a class="item" data-fancybox="img-slide" href="{{\ImageURL::getImageUrl($data->image, 'products', 'original')}}">
                                            <img data-src="{{\ImageURL::getImageUrl($data->image, 'products', 'original')}}" class="lazyload" alt />
                                        </a>
                                    </div>
                                </div>
                                @foreach($data->images as $key => $item)
                                    @if($key < 3)
                                        <div class="product-slide">
                                            <div class="product-image">
                                                <a data-fancybox="img-slide" class="item" href="{{\ImageURL::getImageUrl($item->image, 'products', 'original')}}">
                                                    <img data-src="{{\ImageURL::getImageUrl($item->image, 'products', 'original')}}" class="lazyload" alt />
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="des text-center">
                                <i class="fa fa-search" aria-hidden="true"></i> Click hoặc rê chuột vào ảnh để phóng to
                            </div>
                            <div class="product-thumbnail text-center">
                                @if(!empty($data->lineBox))
                                    <div class="items">
                                        <div class="items-image">
                                            <a href="{{$data->getImageBox('original')}}"  data-fancybox >
                                                <img data-src="{{asset('html-viettech/images/openbox.png')}}" class="lazyload" />
                                            </a>
                                        </div>
                                        <div class="items-desc">Mở hộp</div>
                                    </div>
                                @endif

                                @if(!empty($data->link))
                                    <div class="items">
                                        <a data-fancybox href="{{$data->link}}">
                                            <div class="items-image item-youtube">
                                                <img data-src="{{asset('html-viettech/images/icon-youtube.png')}}" class="lazyload" alt />
                                            </div>
                                        </a>
                                        <div class="items-desc">Video</div>
                                    </div>
                                @endif
                                @php($item_img =count($data->images) - 3)
                                @if($item_img > 0)
                                    <div class="items">
                                        <div class="items-image">
                                            @foreach($data->images as $key => $img_item)
                                                @if($key > 2)
                                                    @if($key == 3)
                                                        <a id="fancybox-thumbs" data-fancybox="images-preview" href="{{\ImageURL::getImageUrl($img_item->image, 'products', 'original')}}" >
                                                            <img data-src="{{asset('html-viettech/images/icon-show-images.png')}}" class="lazyload" alt />
                                                        </a>
                                                    @else
                                                        <a data-fancybox="images-preview" rel="fbt4" href="{{\ImageURL::getImageUrl($img_item->image, 'products', 'original')}}" title="{{$data->title}}" ></a>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>

                                        <div class="items-desc">
                                            Xem thêm
                                            <br />{{$item_img}} ảnh
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="promotion-image">
                                @if(!empty($banner_detail))
                                    <img data-src="{{$banner_detail->getImageUrl('original')}}" class="lazyload" alt />
                                @endif
                            </div>
                        </div>
                        <div id="faq" class="main-wrap faq">
                            <div class="title heading">Hỏi Đáp về {{$data->title}}</div>
                            <form action="{{route('product.question', ['product_id' => $data->id])}}" method="POST">
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
                                                                <span class="time">{{\Lib::dateFormat($item_as->created)}}</span>
                                                            </h5>
                                                            <p>{{$item_as['question']}}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                        <div class="box-reply js-box-reply" style="display: none">
                                            <form action="{{route('product.question', ['product_id' => $data->id, 'qid' => $item->id])}}" method="POST">
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
                        @if ($comment['paginate']->total() > 1)
                            <nav aria-label="Page navigation" class="main-wrap">
                                {{$comment['paginate']->render('FrontEnd::layouts.pagin')}}
                            </nav>
                        @endif
                    </div>
                    <div class="col-lg-5">
                        <div class="group-buy mt-3">
                            <div class="price text-danger">@if($data->out_of_stock == 0){{\Lib::priceFormatEdit($data->price, '')['price']}} đ @else Liên hệ @endif</div>
                            @if($data['out_of_stock'] == 0)
                            <div class="buy-now mt-3">
                                <button class="btn btn-block" type="btn" onclick="shop.addCart({{$data->id}})">
                                    <img data-src="{{asset('html-viettech/images/icon-shopping-cart.png')}}" class="lazyload" alt />Mua ngay
                                </button>
                            </div>
                            @endif
                            <p class="text-center" style="color: #666;">
                                Gọi
                                <strong style="color: #D0021B;">{{$def['hotline']}}</strong> để được tư vấn mua hàng ( Miễn phí)
                            </p>
                        </div>
                        @if(!empty($inFo))
                            <div class="sidebar-right specifications">
                                <div class="title">Thông số kỹ thuật</div>
                                <div class="specifications-desc">
                                    @foreach($inFo as $key => $item_info)
                                        @if($key < 1)
                                            @foreach($item_info->props as $props)
                                                <div class="desc-detail">
                                                    <span>{{$props->title}} :</span>
                                                    <span>{{$props->value}}</span>
                                                </div>
                                            @endforeach
                                        @endif
                                    @endforeach

                                    <div class="view-detail">
                                        <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#modalSpecifications">
                                            Xem cấu hình chi tiết
                                            <img data-src="{{asset('html-viettech/images/icon-arrow-blue.png')}}" class="lazyload" alt />
                                        </button>
                                    </div>
                                </div>
                                <div class="modal fade" id="modalSpecifications">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Thông số kỹ thuật chi tiết</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <ul class="fs-dttsktul list-unstyled" style="max-width : 100%;">
                                                    @foreach($inFo as $key => $item_info)
                                                        <li class="modal-specifications-title">{{$item_info->title}}</li>
                                                            @foreach($item_info->props as $props)
                                                                <li>
                                                                    <label data-id="49">{{$props->title}} :</label>
                                                                    <span><a target="_blank" href="javascript:;" title="Intel">{{$props->value}}</a></span>
                                                                </li>
                                                            @endforeach
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="similar-accessory">
                            <div class="title">Sản phẩm tương tự</div>
                            <div class="items">
                                @foreach($product_accessory as $item_acc)
                                    <div class="item">
                                        <a href="{{route('product.detail.accessory', ['alias' => $item_acc->alias])}}" class="wrap-img">
                                            <img data-src="{{\ImageURL::getImageUrl($item_acc->image, 'products', 'medium')}}" class="lazyload" alt="">
                                        </a>
                                        <div class="cont">
                                            <a href="{{route('product.detail.accessory', ['alias' => $item_acc->alias])}}" class="name">{{$item_acc->title}}</a>
                                            <div class="price text-danger">@if($item_acc->out_of_stock == 0){{\Lib::priceFormatEdit($item_acc->price, '')['price']}} đ @else Liên hệ @endif</div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>


                        <div class="faq main-wrap faq-mobile d-lg-none mt-4"></div>
{{--                        <pagination class="d-lg-none"></pagination>--}}
                    </div>
                </div>
            </section>

        </div>

    </div>
@endsection