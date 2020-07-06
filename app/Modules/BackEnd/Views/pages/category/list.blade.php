<tr>
    <td class="alert-secondary"@if($sort_col > 1) colspan="{{ $sort_col }}" align="right"@endif>{{ $d->sort }}</td>
    <td class="font-weight-bold {{ $class }}" colspan="{{ $title_col }}">{{ $d->title }}</td>
    <td align="center">{{ $d->id }}</td>
    <td>{{ $d->type() }}</td>
    <td>{{ $d->lang() }}</td>
    <td align="center">{{ \Lib::dateFormat($d->created, 'd/m/Y') }}</td>
    @if(\Lib::can($permission, 'edit'))
        <td align="center"><a href="{{ route('admin.'.$key.'.edit', $d->id) }}" class="text-primary"><i class="fe-edit"></i></a></td>
    @endif
    @if(\Lib::can($permission, 'delete'))
        <td align="center"><a href="{{ route('admin.'.$key.'.delete', $d->id) }}"  class="text-danger" onclick="return confirm('Bạn muốn xóa ?')"><i class="icon-trash icons"></i></a></td>
    @endif
</tr>