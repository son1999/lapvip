<div class="row">
    @if($products->total() > 0)
        @foreach($products as $item)
        <div class="col-12 col-sm-6 col-lg-4 mb-5">
            <div class="p-item item-2">
                <a href="{{route('product.detail',['safe_title' => \Illuminate\Support\Str::slug($item->title_short ? $item->title_short : $item->title), 'id' => $item->id])}}" class="figure">
                    <img src="{{\ImageURL::getImageUrl($item->image, \App\Models\Product::KEY, 'mediumx2')}}" alt="">
                </a>
                <div class="info">
                    <a href="{{route('product.detail',['safe_title' => \Illuminate\Support\Str::slug($item->title_short ? $item->title_short : $item->title), 'id' => $item->id])}}" class="title">{{$item->title}}</a>
                    <span class="price d-block mt-2 ">@if($item->out_of_stock == 0){{\Lib::priceFormatEdit($item->priceStrike)['price']}} đ <span class="standard text-danger">{{\Lib::priceFormatEdit($item->price)['price']}} đ</span> @else <span class="standard text-danger">Liên hệ</span> @endif  </span>
                </div>
                @if($item->priceStrike && $item->out_of_stock == 0)
                    <div class="discount"><span>-{{100- round($item->price/$item->priceStrike*100)}}%</span></div>
                @endif
            </div>
        </div>
        @endforeach
    @else
        <div class="col-12">
            <p class="fs-18">Danh sách sản phẩm đang được cập nhật!</p>
        </div>
    @endif
</div>