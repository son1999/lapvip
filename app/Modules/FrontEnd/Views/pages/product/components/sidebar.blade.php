<div class="col-lg-2">
    <div class="cates-sidebar">
        <div class="title d-lg-none"><span>Chọn danh mục</span><span class="js-sidebar-close text-right">&#10005;</span></div>
        <ul class="list-unstyled grade-1">
            @foreach($menu as $item)

            <li>
                @if($item['title'] != 'TRANG CHỦ' && $item['title'] != 'GIỚI THIỆU' && $item['title'] != 'TIN TỨC' && $item['title'] != 'LIÊN HỆ' && $item['title'] != 'KHUYẾN MẠI' )
                    <a href="#wm-menu-1-{{$item['id']}}" data-toggle="collapse" aria-expanded="false" class="@if(!empty($item['sub'])) dropdown-toggle @endif text-uppercase grade-1">{!! $item['title'] !!}</a>
                    <ul class="collapse list-unstyled grade-2" id="wm-menu-1-{{$item['id']}}">
                        @foreach($category as $sub2)
                            @if($item['title'] == $sub2['title'] && $sub2['status'] > 0 && !empty($sub2['sub']))
                                @foreach($sub2['sub'] as $subcat1)
                                    <li class="active">
                                        <a href="#wm-menu-2-{{$subcat1['id']}}" data-toggle="collapse" aria-expanded="false" class="@if(!empty($subcat1['sub']) && $subcat1['status'] > 0) dropdown-toggle @endif text-uppercase grade-2"><strong>{{$subcat1['title']}}</strong></a>
                                        <ul class="collapse list-unstyled text-capitalize grade-3" id="wm-menu-2-{{$subcat1['id']}}">
                                            @foreach($subcat1['sub'] as $subcat2)
                                                <li class="showactive" ><a href="{{route('category.detail', ['safe_title'=> \Illuminate\Support\Str::slug($subcat2['title']), 'id'=>$subcat2['id']])}}" >&#187; <span>{!! $subcat2['title'] !!}</span></a></li>
                                            @endforeach
                                        </ul>

                                    </li>
                                @endforeach
                            @endif
                        @endforeach
                    </ul>
                @endif
            </li>

            @endforeach


        </ul>
    </div>
</div>
