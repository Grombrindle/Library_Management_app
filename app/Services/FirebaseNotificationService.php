<?php

namespace App\Services;

use App\Enums\NotificationType;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Log; // Use the Log facade

class FirebaseNotificationService
{
    protected $messaging;

    public function __construct()
    {
        $firebase = (new Factory)->withServiceAccount(config('firebase.credentials'));
        $this->messaging = $firebase->createMessaging();
    }

    public function sendNotification($token, NotificationType $type, $title, $body, $image = null, $payload = [])
    {
        if (empty($token)) {
            Log::warning('Attempted to send notification with an empty token.');
            return false;
        }

        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(Notification::create($title, $body, $image))
            ->withData([
                'type' => $type->value,
                'title' => $title,
                'body' => $body,
                'image' => $image,
                'payload' => json_encode($payload),
                'timestamp' => now()->toDateTimeString(),
            ]);

        try {
            $this->messaging->send($message);
            return true;
        } catch (\Exception $e) {
            Log::error('Firebase notification error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendNotificationToTopic($topic, NotificationType $type, $title, $body, $image = null, $payload = [])
    {
        $message = CloudMessage::withTarget('topic', $topic)
            ->withNotification(Notification::create($title, $body, $image))
            ->withData([
                'type' => $type->value,
                'title' => $title,
                'body' => $body,
                'image' => $image,
                'payload' => json_encode($payload),
                'timestamp' => now()->toDateTimeString(),
            ]);

        try {
            $this->messaging->send($message);
            return true;
        } catch (\Exception $e) {
            Log::error('Firebase notification error: ' . $e->getMessage());
            return false;
        }
    }

    public function subscribeToTopic($token, $topic)
    {
        try {
            $this->messaging->subscribeToTopic($topic, [$token]);
            return true;
        } catch (\Exception $e) {
            Log::error('Firebase topic subscription error: ' . $e->getMessage());
            return false;
        }
    }

    public function unsubscribeFromTopic($token, $topic)
    {
        try {
            $this->messaging->unsubscribeFromTopic($topic, [$token]);
            return true;
        } catch (\Exception $e) {
            Log::error('Firebase topic unsubscription error: ' . $e->getMessage());
            return false;
        }
    }
}
