@if(!empty($menuLeft))
    <ul class="nav">
        @foreach ($menuLeft as $menu)
            @php($show=false)
            @if(!empty($menu['perm']))
                @can($menu['perm'], $menu['perm'])
                    @php($show=true)
                @endcan
            @else
                @php($show=true)
            @endif
            @if($show)
                @if(!empty($menu['sub']))
                    <li class="nav-title">{{ $menu['title'] }}</li>
                    @foreach ($menu['sub'] as $sub)
                        @php($show=false)
                        @if(!empty($sub['perm']))
                            @can($sub['perm'], $sub['perm'])
                                @php($show=true)
                            @endcan
                        @else
                            @php($show=true)
                        @endif

                        @if($show)
                            @if(!empty($sub['sub']))
                                <li class="nav-item nav-dropdown">
                                    <a class="nav-link nav-dropdown-toggle" href="javascript:void(0)">
                                        @if (!empty($sub['icon'])) <i class="{{ $sub['icon'] }}"></i> @endif {{ $sub['title'] }}
                                    </a>
                                    <ul class="nav-dropdown-items">
                                        @foreach ($sub['sub'] as $sub2)
                                            @php($show=false)
                                            @if(!empty($sub2['perm']))
                                                @can($sub2['perm'], $sub2['perm'])
                                                    @php($show=true)
                                                @endcan
                                            @else
                                                @php($show=true)
                                            @endif
                                            @if($show)
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ $sub2['link']!='' ? $sub2['link'] : 'javascript:void(0)' }}"@if($sub2['link']!='' && $sub2['newtab'] == 1) target="_blank"@endif>
                                                        @if (!empty($sub2['icon'])) <i class="{{ $sub2['icon'] }}"></i> @endif {{ $sub2['title'] }}
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ $sub['link']!='' ? $sub['link'] : 'javascript:void(0)' }}"@if($sub['link']!='' && $sub['newtab'] == 1) target="_blank"@endif>
                                        @if (!empty($sub['icon'])) <i class="{{ $sub['icon'] }}"></i> @endif {{ $sub['title'] }}
                                    </a>
                                </li>
                            @endif
                        @endif
                    @endforeach
                    <li class="divider"></li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $menu['link']!='' ? $menu['link'] : 'javascript:void(0)' }}"@if($menu['link']!='' && $menu['newtab'] == 1) target="_blank"@endif>
                            @if (!empty($menu['icon'])) <i class="{{ $menu['icon'] }}"></i> @endif {{ $menu['title'] }}
                        </a>
                    </li>
                @endif
            @endif
        @endforeach
        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"><i class="icon-logout"></i> Đăng xuất</a>
        </li>
    </ul>
@endif