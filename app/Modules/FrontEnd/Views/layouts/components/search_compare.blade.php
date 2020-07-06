@if(count($productChild) > 0)
    @foreach($productChild as $item)
        <p> <a href="{{route('com.product.compare', ['pro_parent' => $alias, 'pro_child'=> $item->alias])}}">{{ $item->title }}</a></p>
    @endforeach
@else
    <p><a href="">Nothing found.........................</a></p>
@endif
