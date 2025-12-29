<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Get all notifications untuk user
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadCount = Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'status' => true,
            'data' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    // Mark notification as read
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$notification) {
            return response()->json([
                'status' => false,
                'message' => 'Notifikasi tidak ditemukan'
            ], 404);
        }

        $notification->update(['is_read' => true]);

        return response()->json([
            'status' => true,
            'message' => 'Notifikasi ditandai sudah dibaca'
        ]);
    }

    // Mark all as read
    public function markAllAsRead(Request $request)
    {
        Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'status' => true,
            'message' => 'Semua notifikasi ditandai sudah dibaca'
        ]);
    }

    // Get unread count
    public function unreadCount(Request $request)
    {
        $count = Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'status' => true,
            'unread_count' => $count
        ]);
    }

    // Delete notification
    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$notification) {
            return response()->json([
                'status' => false,
                'message' => 'Notifikasi tidak ditemukan'
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'status' => true,
            'message' => 'Notifikasi dihapus'
        ]);
    }
}
