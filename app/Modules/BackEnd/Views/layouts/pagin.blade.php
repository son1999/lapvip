@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link">Prev</span></li>
            @else
                <li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="{{isset($funcAlias) ? $funcAlias : 'changePage'}}({{ $paginator->currentPage()-1 }} @if(isset($is_ajax)) ,'{{$selector_id_to_fill}}','{{$selector_form_for_get}}','{{$url_get_data}}' @endif  )" rel="prev">Prev</a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="{{isset($funcAlias) ? $funcAlias : 'changePage'}}({{ $page }} @if(isset($is_ajax)) ,'{{$selector_id_to_fill}}','{{$selector_form_for_get}}','{{$url_get_data}}' @endif )">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="{{isset($funcAlias) ? $funcAlias : 'changePage'}}({{ $paginator->currentPage()+1 }} @if(isset($is_ajax)) ,'{{$selector_id_to_fill}}','{{$selector_form_for_get}}','{{$url_get_data}}' @endif )" rel="next">Next</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">Next</span></li>
            @endif
        </ul>
    </nav>
@endif

@if(!isset($is_ajax))
    <script>
        function {{isset($funcAlias) ? $funcAlias : 'changePage'}}(page) {
            shop.setGetParameter('page',page);
            // var form = $('form');
            // if(form.length > 1) {
            //     form = $('#searchForm');
            //     form.append('<input type=hidden value="' + page + '" name="page" />');
            //     form.submit();
            // }
        }
    </script>
@else
    <script>
        function {{isset($funcAlias) ? $funcAlias : 'changePage'}}(page,selector_id_to_fill,selector_form_for_get,url) {
            var formData = document.getElementById(selector_form_for_get);
            formData = formData != null ? toJSONString(formData) : {};
            if(typeof formData == 'object') {
                formData.page = page;

                shop.ajax_popup(url, 'GET', formData, function (json) {
                    if (json.error == 0) {
                        document.getElementById(selector_id_to_fill).innerHTML = json.data;
                    } else {
                        console.log(json);
                    }
                });
            }
        }
        function toJSONString( form ) {
            var obj = {};
            var elements = form.querySelectorAll("input, select, textarea");
            for (var i = 0; i < elements.length; ++i) {
                var element = elements[i];
                var name = element.name;
                var value = element.value;

                if (name) {
                    obj[name] = value;
                }
            }

            return obj;
        }
    </script>
@endif