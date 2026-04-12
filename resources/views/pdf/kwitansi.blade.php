<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #1f2937; background: #fff; }

    .header { background: linear-gradient(135deg, #1e3a5f, #0369a1); color: white; padding: 24px 32px; }
    .header-top { display: flex; justify-content: space-between; align-items: flex-start; }
    .logo-area h1 { font-size: 20px; font-weight: bold; margin-bottom: 2px; }
    .logo-area p { font-size: 11px; color: #93c5fd; }
    .no-kwt { text-align: right; }
    .no-kwt .label { font-size: 10px; color: #93c5fd; }
    .no-kwt .value { font-size: 16px; font-weight: bold; }
    .badge { display: inline-block; background: #22c55e; color: white; padding: 3px 12px; border-radius: 99px; font-size: 11px; font-weight: bold; margin-top: 10px; }

    .body { padding: 24px 32px; }

    .warga-info { display: flex; align-items: center; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid #e5e7eb; }
    .avatar { width: 44px; height: 44px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 18px; font-weight: bold; margin-right: 14px; }
    .warga-name { font-size: 15px; font-weight: bold; color: #111827; }
    .warga-email { font-size: 11px; color: #6b7280; margin-top: 2px; }

    .section-title { font-size: 10px; font-weight: bold; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 10px; }

    .detail-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
    .detail-table tr { border-bottom: 1px solid #f3f4f6; }
    .detail-table tr:last-child { border-bottom: none; }
    .detail-table td { padding: 8px 0; font-size: 12px; }
    .detail-table td:first-child { color: #6b7280; width: 45%; }
    .detail-table td:last-child { font-weight: 600; color: #111827; text-align: right; }

    .total-box { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 14px 16px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .total-label { font-size: 13px; font-weight: bold; color: #1d4ed8; }
    .total-value { font-size: 18px; font-weight: 900; color: #1d4ed8; }

    .ttd-area { display: flex; justify-content: space-between; margin-top: 24px; padding-top: 16px; border-top: 1px solid #e5e7eb; }
    .ttd-box { text-align: center; width: 45%; }
    .ttd-box .ttd-label { font-size: 11px; color: #6b7280; margin-bottom: 40px; }
    .ttd-box .ttd-name { font-size: 12px; font-weight: 600; color: #374151; border-top: 1px solid #9ca3af; padding-top: 4px; }

    .footer { background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 14px 32px; text-align: center; }
    .footer p { font-size: 10px; color: #9ca3af; margin: 2px 0; }

    .status-lunas { color: #16a34a; font-weight: bold; }
    .watermark { position: fixed; bottom: 80px; right: 30px; font-size: 48px; color: rgba(34,197,94,0.08); font-weight: 900; transform: rotate(-30deg); }
</style>
</head>
<body>

<div class="watermark">LUNAS</div>

{{-- Header --}}
<div class="header">
    <div class="header-top">
        <div class="logo-area">
            <h1>Smart Kas RT</h1>
            <p>Sistem Manajemen Keuangan RT Modern</p>
        </div>
        <div class="no-kwt">
            <div class="label">No. Kwitansi</div>
            <div class="value">#KWT-{{ str_pad($pembayaran->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>
    </div>
    <div class="badge">✓ Pembayaran Dikonfirmasi</div>
</div>

{{-- Body --}}
<div class="body">

    {{-- Info Warga --}}
    <div class="warga-info">
        <div class="avatar">{{ strtoupper(substr($pembayaran->user->name, 0, 1)) }}</div>
        <div>
            <div class="warga-name">{{ $pembayaran->user->name }}</div>
            <div class="warga-email">{{ $pembayaran->user->email }}</div>
        </div>
    </div>

    {{-- Detail --}}
    <div class="section-title">Detail Pembayaran</div>
    <table class="detail-table">
        <tr>
            <td>No. Transaksi</td>
            <td>#TRX-{{ str_pad($pembayaran->id, 8, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td>Periode Iuran</td>
            <td>{{ \Carbon\Carbon::parse($pembayaran->iuran->bulan.'-01')->translatedFormat('F Y') }}</td>
        </tr>
        <tr>
            <td>Metode Pembayaran</td>
            <td>{{ $pembayaran->labelMetode() }}</td>
        </tr>
        <tr>
            <td>Tanggal Pembayaran</td>
            <td>{{ ($pembayaran->dibayar_at ?? $pembayaran->created_at)->format('d M Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <td>Tanggal Dikonfirmasi</td>
            <td>{{ $pembayaran->updated_at->format('d M Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <td>Status</td>
            <td class="status-lunas">✓ LUNAS</td>
        </tr>
    </table>

    {{-- Total --}}
    <div class="total-box">
        <span class="total-label">Total Dibayar</span>
        <span class="total-value">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</span>
    </div>

    {{-- TTD --}}
    <div class="ttd-area">
        <div class="ttd-box">
            <div class="ttd-label">Warga</div>
            <div class="ttd-name">{{ $pembayaran->user->name }}</div>
        </div>
        <div class="ttd-box">
            <div class="ttd-label">Bendahara RT</div>
            <div class="ttd-name">Admin RT</div>
        </div>
    </div>

</div>

{{-- Footer --}}
<div class="footer">
    <p>Kwitansi ini diterbitkan secara digital oleh sistem Smart Kas RT</p>
    <p>Dicetak pada {{ now()->format('d M Y, H:i') }} WIB &bull; Dokumen ini sah tanpa tanda tangan basah</p>
</div>

</body>
</html>
