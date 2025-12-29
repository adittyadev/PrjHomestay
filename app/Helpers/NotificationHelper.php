<?php

namespace App\Helpers;

use App\Models\Notification;

class NotificationHelper
{
    public static function create($userId, $type, $title, $message, $data = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    // Notifikasi untuk payment confirmed
    public static function paymentConfirmed($userId, $bookingId, $amount)
    {
        return self::create(
            $userId,
            'payment_confirmed',
            '💳 Pembayaran Dikonfirmasi',
            'Pembayaran Anda sebesar Rp ' . number_format($amount, 0, ',', '.') . ' telah dikonfirmasi.',
            ['booking_id' => $bookingId]
        );
    }

    // Notifikasi untuk payment rejected
    public static function paymentRejected($userId, $bookingId, $reason)
    {
        return self::create(
            $userId,
            'payment_rejected',
            '❌ Pembayaran Ditolak',
            'Pembayaran Anda ditolak. Alasan: ' . $reason,
            ['booking_id' => $bookingId]
        );
    }

    // Notifikasi untuk booking confirmed
    public static function bookingConfirmed($userId, $bookingId, $roomName)
    {
        return self::create(
            $userId,
            'booking_confirmed',
            '✅ Booking Dikonfirmasi',
            'Booking Anda untuk kamar ' . $roomName . ' telah dikonfirmasi.',
            ['booking_id' => $bookingId]
        );
    }

    // Notifikasi untuk review reply
    public static function reviewReply($userId, $reviewId, $adminName)
    {
        return self::create(
            $userId,
            'review_reply',
            '💬 Balasan Ulasan',
            $adminName . ' membalas ulasan Anda.',
            ['review_id' => $reviewId]
        );
    }
}
