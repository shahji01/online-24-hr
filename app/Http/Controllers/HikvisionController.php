<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HikvisionService;

class HikvisionController extends Controller
{
    protected $hikvisionService;

    public function __construct(HikvisionService $hikvisionService)
    {
        $this->hikvisionService = $hikvisionService;
    }

    // Get device info
    public function deviceInfo()
    {
        $data = $this->hikvisionService->getDeviceInfo();
        return response()->json($data);
    }

    // Fetch attendance logs
    public function attendanceLogs()
    {
        $logs = $this->hikvisionService->getAttendanceLogs();
        return response()->json($logs);
    }
}