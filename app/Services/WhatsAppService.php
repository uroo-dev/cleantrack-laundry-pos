<?php

namespace App\Services;

class WhatsAppService
{
    public static function generateLink(string $phone, ?string $message = null): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        $url = "https://wa.me/{$phone}";

        if ($message) {
            $url .= '?text=' . urlencode($message);
        }

        return $url;
    }

    public static function sendOrderNotification(string $phone, string $customerName, string $orderCode, string $status): string
    {
        $messages = [
            'menunggu' => "Halo {$customerName}! Pesanan laundry Anda *{$orderCode}* telah kami terima dan sedang menunggu antrian proses cuci. Terima kasih sudah mempercayakan laundry Anda kepada kami.",
            'dicuci' => "Halo {$customerName}! Pesanan laundry Anda *{$orderCode}* sedang dalam proses pencucian. Kami akan memberi kabar saat sudah selesai.",
            'dikeringkan' => "Halo {$customerName}! Pesanan laundry Anda *{$orderCode}* sedang dalam proses pengeringan.",
            'disetrika' => "Halo {$customerName}! Pesanan laundry Anda *{$orderCode}* sedang dalam proses penyetrikaan.",
            'selesai' => "Halo {$customerName}! Pesanan laundry Anda *{$orderCode}* sudah *SELESAI* dan siap diambil. Silakan datang ke outlet kami. Terima kasih!",
            'diambil' => "Halo {$customerName}! Terima kasih telah mengambil pesanan laundry Anda *{$orderCode}*. Sampai jumpa lagi!",
        ];

        return self::generateLink($phone, $messages[$status] ?? '');
    }

    public static function sendPromo(string $phone, string $message): string
    {
        return self::generateLink($phone, $message);
    }

    public static function getNotificationTemplate(string $status, string $customerName, string $orderCode): string
    {
        $templates = [
            'menunggu' => "Halo {$customerName}! Pesanan laundry Anda *{$orderCode}* telah kami terima dan sedang menunggu proses cuci.",
            'selesai' => "Halo {$customerName}! Pesanan laundry Anda *{$orderCode}* sudah *SELESAI* dan siap diambil.",
            'diambil' => "Terima kasih {$customerName} sudah mengambil pesanan *{$orderCode}*.",
        ];

        return $templates[$status] ?? '';
    }
}
