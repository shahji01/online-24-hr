<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class HikvisionService
{
    protected $client;
    protected $baseUrl;
    protected $username;
    protected $password;

    public function __construct()
    {
        $this->baseUrl = "http://192.168.1.100"; // Replace with your device IP
        $this->username = "admin"; // Device username
        $this->password = "12345"; // Device password

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'auth' => [$this->username, $this->password],
            'verify' => false,
        ]);
    }

    // Get device information
    public function getDeviceInfo()
    {
        try {
            $response = $this->client->get('/ISAPI/System/deviceInfo');
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error("Hikvision API Error: " . $e->getMessage());
            return null;
        }
    }

    // Fetch attendance records
    public function getAttendanceLogs()
    {
        // return 'Get Attendance Logs Two';
        try {
            $response = $this->client->get('/ISAPI/AccessControl/AcsEvent?format=json');
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error("Hikvision API Error: " . $e->getMessage());
            return null;
        }
    }
}
