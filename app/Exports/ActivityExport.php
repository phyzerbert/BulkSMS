<?php

namespace App\Exports;

use App\Activity;
use Maatwebsite\Excel\Concerns\FromCollection;

class ActivityExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Activity::all();
    }
}
