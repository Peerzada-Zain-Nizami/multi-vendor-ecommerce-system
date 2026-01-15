@if($paginator->hasPages())
    <ul class="pagination mb-5">
        @if($paginator->onFirstPage())
            <li class="disabled page-item">
                <a class="page-link" href=""><i class="fa fa-arrow-left"></i></a>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{$paginator->previousPageUrl()}}"><i class="fa fa-arrow-left"></i></a>
            </li>
        @endif
        @if(is_array($elements[0]))
            @foreach($elements[0] as $page => $url)
                  @if($page == $paginator->currentPage())
                        <li class="active page-item">
                            <a class="page-link" href="{{$url}}">{{$page}}</a>
                        </li>
                  @else
                        <li class="page-item">
                            <a class="page-link" href="{{$url}}">{{$page}}</a>
                        </li>
                  @endif
            @endforeach
        @endif
        @if($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{$paginator->nextPageUrl()}}"><i class="fa fa-arrow-right"></i></a>
                </li>
        @endif
    </ul>
@endif
