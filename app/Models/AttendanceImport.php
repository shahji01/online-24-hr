<?php

namespace App\Models;

use App\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class AttendanceImport implements ToModel
{
    private $counterNumber;

    public function __construct()
    {

    }

    public function model(array $row)
    {
        return new Attendance([
            'emp_id'              => $row[0],
            'attendance_date'            => $row[1],
            'clock_in'            => $row[2],
            'clock_out'            => $row[3],
        ]);

    }
}