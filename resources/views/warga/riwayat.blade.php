@extends('layouts.warga')
@section('title', 'Riwayat Pembayaran')
@section('page-title', 'Riwayat Pembayaran')

@section('content')
<div id="riwayatList">
    @include('warga.partials.riwayat_list', ['riwayat' => $riwayat])
</div>

<div class="mt-6">
    {{ $riwayat->links('vendor.pagination.simple-tailwind') }}
</div>


@push('scripts')
<script>
async function pollRiwayat() {
    try {
        const res = await fetch('{{ route("warga.riwayat.poll") }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        document.getElementById('riwayatList').innerHTML = data.html_full;
    } catch (e) {}
}
setInterval(pollRiwayat, 10000);
</script>
@endpush
@endsection
