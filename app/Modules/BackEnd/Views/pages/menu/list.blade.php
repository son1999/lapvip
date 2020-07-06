<tr>
    <td class="alert-secondary"@if($sort_col > 1) colspan="{{ $sort_col }}" align="right"@endif>{{ $d->sort }}</td>
    <td class="font-weight-bold {{ $class }}" colspan="{{ $title_col }}">{{ $d->title }}</td>
    <td>
        @if(!empty($d->link))
            <a href="{{ $d->getLink() }}" target="_blank">{{ $d->link }}</a>
        @else
            ---
        @endif
    </td>
    <td>
        @if(!empty($d->perm))
            {{ $d->perm }}
        @else
            ---
        @endif
    </td>
    <td align="center">
        @if($d->newtab == 1)
            <i class="fa fa-thumbs-up text-success"></i>
        @else
            <i class="fa fa-thumbs-down text-danger"></i>
        @endif
    </td>
    <td align="center">
        @if($d->no_follow == 1)
            <i class="fa fa-thumbs-down text-danger"></i>
        @else
            <i class="fa fa-thumbs-up text-success"></i>
        @endif
    </td>
    <td>{{ $d->lang() }}</td>
    <td>{{ \Lib::dateFormat($d->created, 'd/m/Y') }}</td>
    @if(\Lib::can($permission, 'edit'))
        <td align="center"><a href="{{ route('admin.'.$key.'.edit', $d->id) }}" class="text-primary"><i class="fe-edit"></i></a></td>
    @endif
    @if(\Lib::can($permission, 'delete'))
        <td align="center"><a href="{{ route('admin.'.$key.'.delete', $d->id) }}"  class="text-danger" onclick="return confirm('Bạn muốn xóa ?')"><i class="icon-trash icons"></i></a></td>
    @endif
</tr>