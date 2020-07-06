@if (!empty($comment['comment']))
<div class="list-rating col-12">
    <ul>
        @foreach ($comment['paginate'] as $cmt)
        <li class="rating-item">
            @if(!empty($cmt['uid']))
            <span class="name-rate">{{App\Models\Customer::where('id', $cmt['uid'])->value('fullname')}}</span>
            <span class="star-rate star-{{$cmt['rating']}}"></span>

            <p class="content-rate">{{$cmt['comment']}}</p>

            <time class="date-rate">{{ \Lib::dateFormat($cmt['created'], 'd/m/Y') }}</time>
            @endif
            @foreach ($comment['comment'] as $subcmt)
            @if ($subcmt['comment_parent'] == $cmt['id'])
            <div class="ml-5 mt-2">
                <span class="name-rate">Quản trị viên</span>
                <p class="content-rate">{{$subcmt['comment']}}</p>
                <time class="date-rate">{{ \Lib::dateFormat($subcmt['created'], 'd/m/Y') }}</time>
            </div>
            @endif
            @endforeach
        </li>
        @endforeach
    </ul>
    @if ($comment['paginate']->total() > 5)
    <div class="row justify-content-center mt-3">
        <div class="col-6">
            {{$comment['paginate']->render('FrontEnd::layouts.pagin')}}
        </div>
    </div>
    @endif
</div>

@endif