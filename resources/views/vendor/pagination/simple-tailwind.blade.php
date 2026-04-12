@if ($paginator->hasPages())
<nav class="flex items-center gap-1">
    {{-- Prev --}}
    @if ($paginator->onFirstPage())
        <span class="px-3 py-1.5 text-xs text-gray-300 border border-gray-100 rounded-lg cursor-not-allowed">← Prev</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1.5 text-xs text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">← Prev</a>
    @endif

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1.5 text-xs text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Next →</a>
    @else
        <span class="px-3 py-1.5 text-xs text-gray-300 border border-gray-100 rounded-lg cursor-not-allowed">Next →</span>
    @endif
</nav>
@endif
