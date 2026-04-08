@if ($proizvodi->hasPages())
    <nav aria-label="Stranice proizvoda">
        <ul class="pagination justify-content-center flex-wrap">

            @if ($proizvodi->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $proizvodi->previousPageUrl() }}" rel="prev">&laquo;</a></li>
            @endif

            @php
                $totalPages = $proizvodi->lastPage();
                $currentPage = $proizvodi->currentPage();
                $range = 2; 
            @endphp

            @if ($currentPage > ($range + 2))
                <li class="page-item"><a class="page-link" href="{{ $proizvodi->url(1) }}">1</a></li>
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif

            @for ($i = max(1, $currentPage - $range); $i <= min($totalPages, $currentPage + $range); $i++)
                @if ($i == $currentPage)
                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $proizvodi->url($i) }}">{{ $i }}</a></li>
                @endif
            @endfor

            @if ($currentPage < ($totalPages - $range - 1))
                <li class="page-item disabled"><span class="page-link">...</span></li>
                <li class="page-item"><a class="page-link" href="{{ $proizvodi->url($totalPages) }}">{{ $totalPages }}</a></li>
            @endif

            @if ($proizvodi->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $proizvodi->nextPageUrl() }}" rel="next">&raquo;</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
            @endif
        </ul>
    </nav>
@endif

<style>
.pagination {
    --bs-pagination-active-bg: #0d6efd;
    --bs-pagination-active-border-color: #0d6efd;
    --bs-pagination-hover-color: #0d6efd;
}
.pagination .page-item .page-link {
    border-radius: 50px !important;
    padding: 0.5rem 1rem;
    color: #0d6efd;
    border: 1px solid #dee2e6;
    margin: 0 2px;
    transition: all 0.2s ease;
}
.pagination .page-item.active .page-link {
    background-color: #0d6efd;
    color: #fff;
    border-color: #0d6efd;
}
.pagination .page-item .page-link:hover {
    background-color: #eaf2ff;
    color: #0a58ca;
}
</style>