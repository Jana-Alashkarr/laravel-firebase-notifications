<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    protected $firebaseNotificationService;

    public function __construct(FirebaseService $firebaseNotificationService)
    {
        $this->firebaseNotificationService = $firebaseNotificationService;
    }

    public function sendNotification(Request $request)
    {

        $request->validate([
            'deviceToken' => 'required|string',
            'messageData' => 'required|array',
        ]);

        $deviceToken = $request->input('deviceToken');
        $messageData = $request->input('messageData', []);

        try {

            $title = $messageData['title'] ?? 'Default Title';
            $body = $messageData['body'] ?? 'Default Body';


            $this->firebaseNotificationService->sendNotification($deviceToken, $title, $body, $messageData);

            return response()->json(['message' => 'Notification sent successfully!'], 200);
        } catch (Exception $e) {
            Log::error('Firebase Notification Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function sendMulticastNotification(Request $request)
    {

        $request->validate([
            'deviceTokens' => 'required|array',
            'deviceTokens.*' => 'required|string',
            'messageData' => 'required|array',
        ]);

        $deviceTokens = $request->input('deviceTokens');
        $messageData = $request->input('messageData', []);

        try {
            // Prepare the message
            $title = $messageData['title'] ?? 'Default Title';
            $body = $messageData['body'] ?? 'Default Body';

            // Send the notification using the service
            $responses = $this->firebaseNotificationService->sendMulticastNotification($deviceTokens, $title, $body, $messageData);

            return response()->json(['message' => 'Multicast notification sent successfully!', 'successCount' => $responses['successCount'], 'failureCount' => $responses['failureCount']], 200);
        } catch (Exception $e) {
            Log::error('Firebase Multicast Notification Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
