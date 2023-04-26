@push('css')
<style type="text/css">
    .hide{ display: none;  }
</style>
@endpush
<?php
// config
$link_limit = 7; // maximum number of links (a little bit inaccurate, but will be ok for now)
?>

@if ($paginator->lastPage() > 1)
    <div class="bottom-pagination">
        <ul>
             
            <li class="{{ ($paginator->currentPage() == 1) ? ' disabled hide' : '' }}">
                <a href="{{ $paginator->url(1) }}"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
            </li>
            @for ($i = 1; $i <= $paginator-> lastPage(); $i++)
                <?php
                $half_total_links = floor($link_limit / 2);
                $from = $paginator->currentPage() - $half_total_links;
                $to = $paginator->currentPage() + $half_total_links;
                if ($paginator->currentPage() < $half_total_links) {
                    $to += $half_total_links - $paginator->currentPage();
                }
                if ($paginator->lastPage() - $paginator->currentPage() < $half_total_links) {
                    $from -= $half_total_links - ($paginator->lastPage() - $paginator->currentPage()) - 1;
                }
                ?>
                @if ($from < $i && $i < $to)
                    <li class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
                        <a href="{{ $paginator->url($i) }}">{{ $i }}</a>
                    </li>
                @endif
            @endfor
           <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled hide' : '' }}">
                <a href="{{ $paginator->url($paginator->lastPage()) }}"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
            </li>
        </ul>
    </div>
@endif


{{--    <ul>--}}
{{--        <li><a href=""><i class="fa fa-arrow-left" aria-hidden="true"></i></a></li>--}}
{{--        <li class="active"><a href="">1</a></li>--}}
{{--        <li><a href="">2</a></li>--}}
{{--        <li><a href="">3</a></li>--}}
{{--        <li><a href="">4</a></li>--}}
{{--        <li><a href=""><i class="fa fa-arrow-right" aria-hidden="true"></i></a></li>--}}
{{--    </ul>--}}

