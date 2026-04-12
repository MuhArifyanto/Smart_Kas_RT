<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 20px; }
    .wrap { max-width: 560px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    .header { background: linear-gradient(135deg, #1e3a5f, #0369a1); padding: 28px 32px; text-align: center; }
    .header h1 { color: white; margin: 0 0 4px; font-size: 22px; }
    .header p { color: #93c5fd; margin: 0; font-size: 13px; }
    .badge { display: inline-block; background: #22c55e; color: white; padding: 5px 16px; border-radius: 99px; font-size: 12px; font-weight: bold; margin-top: 12px; }
    .body { padding: 28px 32px; }
    .greeting { font-size: 15px; color: #374151; margin-bottom: 8px; }
    .sub { font-size: 13px; color: #6b7280; margin-bottom: 24px; line-height: 1.6; }
    .card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px; margin-bottom: 16px; }
    .card-title { font-size: 10px; font-weight: bold; color: #9ca3af; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 12px; }
    .row { display: flex; justify-content: space-between; padding: 7px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; }
    .row:last-child { border-bottom: none; }
    .lbl { color: #6b7280; }
    .val { font-weight: 600; color: #111827; }
    .total { background: #eff6ff; border-radius: 8px; padding: 14px 16px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .total-l { color: #1d4ed8; font-weight: 700; font-size: 14px; }
    .total-v { color: #1d4ed8; font-weight: 900; font-size: 18px; }
    .pdf-note { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; font-size: 12px; color: #166534; }
    .btn { display: inline-block; padding: 11px 22px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; margin: 4px; }
    .btn-primary { background: #2563eb; color: white; }
    .btn-secondary { background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }
    .footer { background: #f8fafc; padding: 18px 32px; text-align: center; border-top: 1px solid #e5e7eb; }
    .footer p { color: #9ca3af; font-size: 11px; margin: 3px 0; }
</style>
</head>
<body>
<div class="wrap">

    <div class="header">
        <h1>Smart Kas RT</h1>
        <p>Sistem Manajemen Keuangan RT Modern</p>
        <div class="badge">✓ Pembayaran Berhasil Dikonfirmasi</div>
    </div>

    <div class="body">
        <p class="greeting">Halo, <strong>{{ $pembayaran->user->name }}</strong>!</p>
        <p class="sub">
            Pembayaran iuran Anda telah berhasil dikonfirmasi oleh admin RT.
            Bukti pembayaran dalam format PDF terlampir di email ini.
        </p>

        <div class="card">
            <div class="card-title">Detail Pembayaran</div>
            <div class="row">
                <span class="lbl">No. Kwitansi</span>
                <span class="val">#KWT-{{ str_pad($pembayaran->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="row">
                <span class="lbl">No. Transaksi</span>
                <span class="val">#TRX-{{ str_pad($pembayaran->id, 8, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="row">
                <span class="lbl">Nama Warga</span>
                <span class="val">{{ $pembayaran->user->name }}</span>
            </div>
            <div class="row">
                <span class="lbl">Periode Iuran</span>
                <span class="val">{{ \Carbon\Carbon::parse($pembayaran->iuran->bulan.'-01')->translatedFormat('F Y') }}</span>
            </div>
            <div class="row">
                <span class="lbl">Metode Pembayaran</span>
                <span class="val">{{ $pembayaran->labelMetode() }}</span>
            </div>
            <div class="row">
                <span class="lbl">Tanggal Dikonfirmasi</span>
                <span class="val">{{ $pembayaran->updated_at->format('d M Y, H:i') }} WIB</span>
            </div>
            <div class="row">
                <span class="lbl">Status</span>
                <span class="val" style="color:#16a34a;">✓ LUNAS</span>
            </div>
        </div>

        <div class="total">
            <span class="total-l">Total Dibayar</span>
            <span class="total-v">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</span>
        </div>

        <div class="pdf-note">
            📎 <strong>Bukti pembayaran PDF</strong> terlampir di email ini.
            Simpan sebagai arsip pembayaran iuran Anda.
        </div>

        <div style="text-align:center;">
            <a href="{{ url('/kwitansi/' . $pembayaran->id) }}" class="btn btn-primary">
                📄 Lihat Kwitansi Online
            </a>
            <a href="{{ url('/warga/riwayat') }}" class="btn btn-secondary">
                📋 Riwayat Pembayaran
            </a>
        </div>
    </div>

    <div class="footer">
        <p>Email ini dikirim otomatis oleh sistem Smart Kas RT.</p>
        <p>Jangan balas email ini. Hubungi admin RT jika ada pertanyaan.</p>
        <p style="margin-top:6px;">© {{ date('Y') }} Smart Kas RT</p>
    </div>

</div>
</body>
</html>
