<?php

namespace App\Exports;

use App\Activity;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ActivityExport implements FromArray, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        $data =  Activity::distinct('phone_number')->get();
        $activities = array();
        $i = 0;
        foreach ($data as $item) {
            $activities[$i] = array();
            $activities[$i]['no'] = $i+1;
            $activities[$i]['phone_number'] = $item->phone_number;
            $activities[$i]['date_time'] = $item->creatd_at;
            $i++;
        }
        return $activities;
    }
    
    public function headings(): array
    {
        return [
            'No',
            'Phone Number',
            'Date & Time',
        ];
    }
}
