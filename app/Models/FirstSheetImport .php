<?php

namespace App\Imports;
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class FirstSheetImport implements ToCollection
{

    public function __construct()
    {
        echo "a";
    }
    public function collection(Collection $rows)
    {
        print_r($rows);
    }

    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        info("Sheet {$sheetName} was skipped");
    }
}









?>