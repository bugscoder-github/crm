<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;

class NotificationService {
    public function getUnreadNotificationsCount() {
        $user = Auth::user();
        if ($user) {
            // return $user->unreadNotifications()->count();
        }

        return 0;
    }

    public function getUnreadNotifications() {
        $user = Auth::user();
        if ($user) {
            // return $user->unreadNotifications()->get();
        }

        return collect(); // Return empty collection if no user is authenticated
    }
}
?>