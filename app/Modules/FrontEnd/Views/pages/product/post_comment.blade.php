<div class="col-12 col-lg-4 d-flex align-items-center justify-content-center justify-content-lg-end">
    <a href="javascript:;" @if(!Auth::guard('customer')->check()) data-toggle="modal" data-target="#login" class="btn-rate" @else class="show-form-rate" @endif>Viết nhận xét của bạn</a>
</div>
<div class="col-12 form-rate" style="display: none">
    <form id="post_comment" method="POST" action="{{route('product.comment', ['post_id' => $product->id])}}">
        @csrf
        <input type="text" name="obj[name]" @if(Auth::guard('customer')->check()) disabled value="{{Auth::guard('customer')->user()->fullname}}" @endif class="form-control" placeholder="Họ và tên">
        {{-- <input type="text" name="obj[email]" class="form-control" placeholder="Email"> --}}
        <textarea name="obj[comment]"  class="form-control" cols="30" rows="5"></textarea>
        <div class="rating">
            <span><i class="fa fa-star" aria-hidden="true"></i></span>
            <input type="radio" value="1" name="obj[rating-product]" class="rating-product">

            <span><i class="fa fa-star" aria-hidden="true"></i></span>
            <input type="radio" value="2" name="obj[rating-product]" class="rating-product">

            <span><i class="fa fa-star" aria-hidden="true"></i></span>
            <input type="radio" value="3" name="obj[rating-product]" class="rating-product">
            
            <span><i class="fa fa-star" aria-hidden="true"></i></span>
            <input type="radio" value="4" name="obj[rating-product]" class="rating-product">
            
            <span><i class="fa fa-star" aria-hidden="true"></i></span>
            <input type="radio" value="5" name="obj[rating-product]" class="rating-product" checked>
        </div>
        <button>Gửi</button>
    </form>
</div>