<?php

use Illuminate\Support\Carbon;

if (!function_exists('generateInvoice')) {
    function generateInvoice($duration)
    {
        // Ambil waktu sekarang dalam milidetik
        $now = Carbon::now();
        $timestampMilliseconds = $now->timestamp * 1000 + intval($now->format('v')); // 'v' adalah format untuk milidetik

        // Tambahkan durasi ke waktu (opsional)
        $newTimestamp = $timestampMilliseconds + ($duration * 1000); // Durasi dalam detik dikonversi ke milidetik

        // Buat prefix dengan format INV_
        $invoiceNumber = 'INV_' . $newTimestamp;

        return $invoiceNumber;
    }
}
