<table class="table table-bordered table-striped table-responsive">
    <thead>
    <tr>
        <th width="55">STT</th>
        <th>Ảnh</th>
        <th>Sản phẩm</th>
        <th>Giá</th>
        <th>Số lượng</th>
        <th>Thành tiền</th>
    </tr>
    </thead>
    <tbody>
    @php $count_item = 0 @endphp
    @foreach($data->items as $itm)
    <tr>
        <td align="center">1</td>
        <td>
            <img src="{{$itm->getImageURL('small')}}" alt="" width="80px">
        </td>
        <td>
            <a target="_blank" href="{{route('product.detail',['safe_title' => \Illuminate\Support\Str::slug($itm->name),'id'=> $itm->product_id])}}">{{ $itm->name }}</a>
            @if($itm['opts'] != '')
            <div>
            @php $metas = json_decode($itm['opts']) @endphp
            @foreach($metas as $meta)
                <span>{{$meta->filter_cate_title}} : <b>{{$meta->filter_value}}</b></span>
            @endforeach
            </div>
            @endif
        </td>
        <td align="right">{{ \Lib::priceFormatEdit($itm->price)['price']}}<sup class="text-danger">đ</sup></td>
        <td align="right">{{ $itm->quantity }}</td>
        <td align="right">{{ \Lib::priceFormatEdit($itm->price*$itm->quantity)['price']}}<sup class="text-danger">đ</sup></td>
    </tr>
    @php $count_item += $itm->quantity @endphp
    @endforeach
    <tr>
        <td colspan="4" align="right"><b>Phí ship</b></td>
        <td align="right"></td>
        <td align="right">{{ \Lib::priceFormatEdit($data->fee_shipping)['price']}}<sup class="text-danger">đ</sup></td>
    </tr>
    <tr>
        <td colspan="4" align="right"><b>Tổng cộng</b></td>
        <td align="right">{{ $count_item }}</td>
        <td align="right">{{ \Lib::priceFormatEdit($data->total_price + $data->fee_shipping)['price']}}<sup class="text-danger">đ</sup></td>
    </tr>
    </tbody>
</table>