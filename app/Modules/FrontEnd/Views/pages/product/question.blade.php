<div class="offset-0 offset-lg-1 col-12 col-lg-10">
    <h4 class="emtry-title">Hỏi đáp về sản phẩm</h4>
    <div class="faq faq-products">
        <div class="content">
            @foreach ($comment['question'] as $item)
            <div class="faq-content">
                <p>
                    <strong>{{$item['question']}}</strong>
                    @if (!empty($item['answer']))
                    {{$item['answer']}}
                    <span>Quản trị viên trả lời vào {{ \Lib::dateFormat($item['created'], 'd/m/Y') }}</span>
                    @endif
                </p>
            </div>
            @endforeach
        </div>
        <form action="{{route('product.question', ['product_id' => $product->id])}}" method="POST">
            @csrf
            <div class="form-group">
                <input type="text" name="question" required class="form-control" id="exampleInputtext" placeholder="Hãy đặt câu hỏi liên quan đến sản phẩm..."/>
                <button class="btn send-question" @if(!Auth::guard('customer')->check()) type="button" data-toggle="modal" data-target="#login" @else type="submit" @endif>Gửi câu hỏi</button>
            </div>
        </form>
    </div>
</div>