<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Exception;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount([
         "type"=> "service_account",
  "project_id"=> "car-shop-be917",
  "private_key_id"=> "51fb30a4cfd82135831172b9bf386c961662aef4",
  "private_key"=> "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDDNFetfbihqI7+\noUyVZzkft1VfVrH9R3jEcI/GrZzN/2B4cyC2anIDoC3Pf/5bg8J0ZgP3Fxv+LryU\n5VynBAWnxPJS8zsCIT9AA25pg/1HiR0bEfYuryxnj2pQlLYTsGudiD0nhyyyZ3r+\n0x5ki1n9kYFTEuXM14aLwa57KZbWUgrW6A/iTymnJO22945uI3kT9dBMxmzFQW5H\nbHkRQi7x5JICb0xwyNT9/I+xkrRNUjq2rjyQdTggT9FaKXRmafRkOZJecP/sBzbr\nREmhH617ZdijH2apWujtcEc4Y+gx8uFIvEG0bhiPBrB+UJk6kgxOgEMUH6TNGf0V\noYQw5ZOjAgMBAAECggEAC5BNOrIo6YKyaIkmSpohJdeBejKzkT0rp/oXD6j3PLN1\nLo17ZvMAABD8GxUKUgzdzHFtU1+OFqJZejnqzDC5I7V6DsnpOU4TkMzjPI2cx8u/\n6qZHaX/nvIF1cIKZaqnX0NmM7eDcbVEjVcyB5L47PYHiIbQL4vTy5n05wy1f3yBF\ns+gR/u6GC1QkpqvK9BHcin+gtEAnAVbZOnjxIfJjuZMC+aZrvQdh0+GE05heXW6V\nlfTZNK/2dsqbROIATA1VUEJ/SKathq/M/oKkpXtNE91n69Xjdw3o5JCY891qekHh\nFQKFI3jCbMJ9tSgSG14Nu4HiiQHTAVrOAbX77r1YYQKBgQD+WmtKRtBisk7orr9F\nnkIXFyqN2f44qQc2lj4eWzfUp8I7iuzdoG5mRv1KbTG3dHrgu9Q+2K83hF2yhz5M\nXaye5mgvh0Wa+Y/2nf88TYElPlbUr+F+WU3lpes+bSnS2JPXzZJj9Cqo8VqkGY5x\n6VZK0Xeu8sLUJNxTXW8cMwzp8QKBgQDEd+L2gzeV+RDW17PyOGKVmDPqHn+hiazO\nKtnB+uQbeTBCPuKXYZEKP9I7JhmC+0l98oO1kQMCNTqgzYQDySdzaTaW95IHYGjl\nuV5ts0d/swlyVt4IhFw7XN+weFac2tTTtB4kIZjg9DwHq056WbqjpYu1PTs6nEvc\nMgKZ+Fri0wKBgQDY9I1BrQeAuDFAnhW+r1AWXAdLOd9zuxHRCPRxdkM4G+Q8X7LN\nFFQ232ScAGoA3tUVLoHLHY7PXxOA/YUxJFHitAu4Rr0jhK28oWYdrMp01yi/gEpq\nOIiOUylGdVzQYTYyREITCij9M+mpwbbUCUE2zlc1HhL7W3mnjjIBLrZcMQKBgEW9\nBHxaYX3Dth183awKJbxSFYNyJf3SH9viy/8WLqgt4Vpydf4kLNbFhrtmL8IVrqWd\nUvE9MyMyf8gai1TIr09BNpZp9JTXvQRmQ0WPUL7cb2r9uLyvNwn/UouSe7Qb3VX0\nZoqOvnSDXVefkDDP7vctySShQofweOEFg3Th+mjvAoGBALWoGJkk1km1NGDinVZ0\n8xSvKsGXKzgm7Kq+PTtvhCe3W6r63NlO9EjIkx26jWV2BDt9x89zoDrLxAF0FWpq\nDG5ppbp2e9LliUfkoyrU8RDiryirAnQ840XpjjR3e2s36/w42BxBnZnLc/+pMPuI\n29F/6pk+V4QrkK53U2cXKY4s\n-----END PRIVATE KEY-----\n",
  "client_email"=> "firebase-adminsdk-8b4e2@car-shop-be917.iam.gserviceaccount.com",
  "client_id"=> "110307097711088281531",
  "auth_uri"=> "https://accounts.google.com/o/oauth2/auth",
  "token_uri"=> "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url"=> "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url"=> "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-8b4e2%40car-shop-be917.iam.gserviceaccount.com",
  "universe_domain"=> "googleapis.com"
        ])
        ->withProjectId(env('FIREBASE_PROJECT_ID'));

        $this->messaging = $factory->createMessaging();
    }


    public function sendNotification($token, $title, $body, $data = [])
    {
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        try {
            $response = $this->messaging->send($message);
            Log::info('Firebase Response: ' . json_encode($response));
            return $response;
        } catch (Exception $e) {
            Log::error('Firebase Notification Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function sendMulticastNotification($tokens, $title, $body, $data = [])
    {
        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        try {
            $responses = $this->messaging->sendMulticast($message, $tokens);

            $successCount = $responses->successes()->count();
            $failureCount = $responses->failures()->count();

            foreach ($responses->failures()->getItems() as $failure) {
                Log::error('Failed to send to token ' . $failure->target()->value() . ': ' . $failure->error()->getMessage());
            }

            return [
                'successCount' => $successCount,
                'failureCount' => $failureCount,
                'responses' => $responses,
            ];
        } catch (Exception $e) {
            Log::error('Firebase Multicast Notification Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
