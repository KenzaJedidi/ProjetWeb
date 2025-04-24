<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Twilio\Rest\Client;

class SmsService {
    private $account_sid;
    private $auth_token;
    private $twilio_number;
    private $client;

    public function __construct() {
        // Load config securely
        $config = require_once __DIR__ . '/../config/twilio.php';
        
        $this->account_sid = $config['account_sid'];
        $this->auth_token = $config['auth_token'];
        $this->twilio_number = $config['twilio_number'];
        
        $this->client = new Client($this->account_sid, $this->auth_token);
    }

    public function sendVerificationCode($phone_number, $code) {
        try {
            // Add error logging
            if(!preg_match('/^[0-9]{8}$/', $phone_number)) {
                throw new Exception('Invalid phone number format');
            }

            $message = $this->client->messages->create(
                '+216' . $phone_number,
                [
                    'from' => $this->twilio_number,
                    'body' => "Your AllergiCare verification code is: $code"
                ]
            );
            
            // Log success
            error_log("SMS sent successfully to +216{$phone_number}");
            return true;
        } catch (Exception $e) {
            // Log error
            error_log("SMS sending failed: " . $e->getMessage());
            return false;
        }
    }
}