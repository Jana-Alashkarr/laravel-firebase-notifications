
# Laravel Firebase Notifications

This is a Laravel-based project that provides REST API endpoints to send push notifications using Firebase Cloud Messaging (FCM). It supports both single device and multicast notifications.

## 🔧 Features

- Send notification to a single device via token.
- Send notification to multiple devices (multicast).
- Laravel validation and error handling.
- Service-based architecture using a dedicated FirebaseService class.

## 📦 Technologies Used

- Laravel 10+
- Kreait Firebase PHP SDK
- Firebase Cloud Messaging (FCM)
- PHP 8+

## 🚀 API Endpoints

### ✅ Send Notification to Single Device

**POST** `/api/send-notification`

**Request Body:**

```json
{
  "deviceToken": "YOUR_DEVICE_TOKEN",
  "messageData": {
    "title": "Hello",
    "body": "This is a notification",
    "customKey": "customValue"
  }
}
=======


