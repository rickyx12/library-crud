@if ($paginator->hasPages())
    <ul class="pagination">

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><span>{{ $page }}</span></li>
                    @else

                        @if( preg_match("/\ball\b/i", $url, $match) )

                            <li>
                                <a class='allBook' href="{{ url($url) }}">
                                    {{ $page }}
                                </a>
                            </li>
                        
                        @elseif( preg_match("/\bfilter\b/i", $url, $match) )
                            
                            <li>
                                <a class='filteredBook' href="{{ url($url) }}">
                                    {{ $page }}
                                </a>
                            </li>
                        
                        @elseif( preg_match("/\bsearch\b/i", $url, $match) )

                            <li>
                                <a class='searchBook' href="{{ url($url) }}">
                                    {{ $page }}
                                </a>
                            </li>

                        @else
                        @endif

                    @endif
                @endforeach
            @endif
        @endforeach

    </ul>
@endif
