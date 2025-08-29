<?php

namespace App\Http\Controllers;

use App\Enums\NotificationType;
use App\Models\Notification;
use App\Services\FirebaseNotificationService;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class NotificationsController extends Controller
{

    protected $firebaseServices;

    public function __construct(FirebaseNotificationService $firebaseServices)
    {
        $this->firebaseServices = $firebaseServices;
    }

    public function __invoke()
    {
        try {
            $customer = Auth::user();
            $notifications = $customer->notifications;

            if ($notifications->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'result' => [],
                    'statusCode' => 404,
                    'message' => 'No notifications found',
                    'error' => null,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'result' => $notifications,
                'statusCode' => 200,
                'message' => 'Notifications retrieved successfully',
                'error' => null,
            ], 200);


        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'result' => null,
                'statusCode' => 500,
                'message' => $e->getMessage(),
                'error' => $e,
            ], 500);
        }

    }

    public function sendPushNotification(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'type' => 'required|string',
            'title' => 'required|string',
            'body' => 'required|string',
            'image' => 'nullable|url',
            'payload' => 'nullable|array',
        ]);

        try {
            $token = $request->input('token');
            $type = NotificationType::from($request->input('type'));
            $title = $request->input('title');
            $body = $request->input('body');
            $image = $request->input('image');
            $payload = $request->input('payload', []);

            $this->firebaseServices->sendNotification(
                $token,
                $type,
                $title,
                $body,
                $image,
                $payload
            );
            // ...
            $customer = Auth::user();
            // ...
            $customer->notifications()->create([
                'type' => $type,
                'title' => $title,
                'body' => $body,
                'image' => $image,
                'payload' => json_encode($payload),
                'timestamp' => now(),
            ]);
            // ...

            return response()->json([
                'success' => true,
                'result' => null,
                'statusCode' => 200,
                'message' => 'notifcation send successfully',
                'error' => null,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'result' => null,
                'statusCode' => 500,
                'message' => 'Failed to send notification',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getNotifications(Request $request)
    {
        try {
            $request->validate([
                'size' => 'integer',
                'page' => 'integer'
            ]);

            $size = $request->input('size', 10);
            $page = $request->input('page', 1);

            $notifications = Auth::user()
                ->notifications()
                ->orderBy('timestamp', 'desc')
                ->paginate($size, ['*'], 'page', $page)
                ->through(fn($notification) => [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'body' => $notification->body,
                    'image' => $notification->image,
                    'timestamp' => $notification->timestamp ? Carbon::parse($notification->timestamp)->format('Y-m-d\TH:i:s.v\Z') : null,
                    'isRead' => $notification->is_read,
                    'payload' => $notification->payload ? json_decode($notification->payload, true) : null,
                ]);

            return response()->json([
                'success' => true,
                'result' => [
                    'data' => $notifications->items(),
                    'pagination' => [
                        'current_page' => $notifications->currentPage(),
                        'last_page' => $notifications->lastPage(),
                        'per_page' => $notifications->perPage(),
                        'total' => $notifications->total()
                    ]
                ],
                'statusCode' => 200,
                'message' => 'Notifications retrieved successfully',
                'error' => null,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'result' => null,
                'statusCode' => 500,
                'message' => 'Failed to fetch notifications',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function sendPushNotificationToAllUsers(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'title' => 'required|string',
            'body' => 'required|string',
            'image' => 'nullable|url',
            'payload' => 'nullable|array',
        ]);

        try {
            $type = NotificationType::from($request->input('type'));
            $title = $request->input('title');
            $body = $request->input('body');
            $image = $request->input('image');
            $payload = $request->input('payload', []);

            $this->firebaseServices->sendNotificationToTopic(
                'top_students',
                $type,
                $title,
                $body,
                $image,
                $payload
            );

            return response()->json([
                'success' => true,
                'message' => 'Notification sent to all users successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification to all users',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function setToken(Request $request)
    {
        $request->validate(['fcm_token' => 'required|string']);

        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'result' => null,
                    'statusCode' => 401,
                    'message' => 'Unauthorized',
                    'error' => 'User not authenticated',
                ], 401);
            }

            $user->update(['fcm_token' => $request->input('fcm_token')]);

            return response()->json([
                'success' => true,
                'result' => null,
                'statusCode' => 200,
                'message' => 'FCM token saved successfully',
                'error' => null,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'result' => null,
                'statusCode' => 500,
                'message' => 'Failed to save FCM token',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        try {
            // $user = Auth::user()->fcm_token;
            $user = Auth::user();

            $user->fcm_token = $request->fcm_token;
            $user->save();

            $this->firebaseServices->subscribeToTopic($request->fcm_token, 'top_students');

            return response()->json([
                'success' => true,
                'message' => 'FCM token updated successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update FCM token',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // public function getNotificationPreferences()
    // {
    //     try {
    //         $user = Auth::user();

    //         return response()->json([
    //             'success' => true,
    //             'preferences' => [
    //                 'notification_enabled' => (bool) $user->notification_enabled,
    //                 'notification_sound_enabled' => (bool) $user->notification_sound_enabled,
    //             ]
    //         ]);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to fetch notification preferences',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    // public function updateNotificationPreferences(Request $request)
    // {
    //     $request->validate([
    //         'notification_enabled' => 'sometimes|boolean',
    //         'notification_sound_enabled' => 'sometimes|boolean',
    //     ]);

    //     try {
    //         $user = Auth::user();

    //         if ($request->has('notification_enabled')) {
    //             $user->notification_enabled = $request->notification_enabled;
    //         }

    //         if ($request->has('notification_sound_enabled')) {
    //             $user->notification_sound_enabled = $request->notification_sound_enabled;
    //         }

    //         $user->save();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Notification preferences updated successfully',
    //         ]);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to update notification preferences',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }



    // public function testNotificationSound(Request $request)
    // {
    //     try {
    //         $user = Auth::user();

    //         if (!$user) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Unauthorized',
    //                 'error' => 'User not authenticated',
    //             ], 401);
    //         }
    //         \Log::info('Testing notification for user:', [
    //             'user_id' => $user->id,
    //             'fcm_token' => $user->fcm_token,
    //             'notification_enabled' => $user->notification_enabled,
    //             'notification_sound_enabled' => $user->notification_sound_enabled
    //         ]);

    //         $token = $user->fcm_token;
    //         $type = NotificationType::from('systemUpdate');
    //         $title = "Sound Test Notification";
    //         $body = "This is a test notification. Sound should be " .
    //             ($user->notification_sound_enabled ? "ON" : "OFF");
    //         $image = null;
    //         $payload = [
    //             'test' => true,
    //             'sound_enabled' => $user->notification_sound_enabled
    //         ];

    //         $result = $this->firebaseServices->sendNotification(
    //             $token,
    //             $type,
    //             $title,
    //             $body,
    //             $image,
    //             $payload
    //         );

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Test notification sent',
    //             'settings' => [
    //                 'notification_enabled' => (bool) $user->notification_enabled,
    //                 'notification_sound_enabled' => (bool) $user->notification_sound_enabled,
    //                 'fcm_token' => $user->fcm_token
    //             ],
    //             'notification_sent' => $result
    //         ]);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to send test notification',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

}
