<?php

namespace App\Mail;

use App\Models\Pembayaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KwitansiPembayaran extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Pembayaran $pembayaran) {}

    public function envelope(): Envelope
    {
        $bulan = \Carbon\Carbon::parse($this->pembayaran->iuran->bulan . '-01')->translatedFormat('F Y');

        return new Envelope(
            subject: "✅ Pembayaran Berhasil - Kwitansi Iuran RT {$bulan}",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.kwitansi');
    }

    public function attachments(): array
    {
        // Generate PDF dari template blade
        $pdf = Pdf::loadView('pdf.kwitansi', ['pembayaran' => $this->pembayaran])
            ->setPaper('a4', 'portrait');

        $filename = 'Kwitansi-KWT-' . str_pad($this->pembayaran->id, 6, '0', STR_PAD_LEFT) . '.pdf';

        return [
            Attachment::fromData(
                fn () => $pdf->output(),
                $filename
            )->withMime('application/pdf'),
        ];
    }
}
