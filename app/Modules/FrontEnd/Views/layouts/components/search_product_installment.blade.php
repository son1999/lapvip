@if(count($data) > 0)
    @foreach($data as $item)
        <p> <a href="{{route('installment.scenarios', ['alias' => $item->alias, '_token' => csrf_token(),'index' => $index, 'id' => $item->id, 'quan'=>1])}}">{{ $item->title }}</a></p>
    @endforeach
@else
    <p><a href="">Nothing found.........................</a></p>
@endif
