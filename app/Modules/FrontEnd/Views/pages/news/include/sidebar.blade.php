
<div class="sidebar_blog">
    <h3>Bài viết khác</h3>
    <div class="sidebar--list-menu">
        <ul class="list-group">
            @foreach ($related as $item)
            <li><a href="{{route('news.detail',['safe_title' => \Illuminate\Support\Str::slug($item->title), 'id' => $item->id])}}"><i class="fa fa-caret-right"></i> {!! mb_substr($item['title'], 0, 130) !!}&nbsp;...</a> </li>
            @endforeach
        </ul>
    </div>
</div>