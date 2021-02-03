<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Spatie\Browsershot\Browsershot;

class ReportController extends Controller
{
    public function genData()
    {
        $step = 200;
        $data = [];
        $day = 3 * 24 * 60 * 60;
        for($index = 0; $index < $step; $index++) {
            $date = Carbon::today()->addSeconds($day * $index / $step)->toDateTimeString();
            $value = $index == 0 ? rand(0, 60) : $data[$index-1]->value + rand(-1, 1);
            $value = $value < 0 ? 0 : $value;
            $value = $value > 60 ? 60 : $value;
            array_push($data, (object) [
                'date' => $date,
                'value' => $value,
            ]);
        }

        return json_encode($data);
    }

    public function export()
    {
        $data = [
            'data' => $this->genData(),
            'thresholds' => [
                'acceptable' => 5,
                'danger' => 10,
            ],
        ];

        $html = view('reports.carbon-monoxide', compact('data'))->render();
        $width = 1202;
        $height = 933;

        return Browsershot::html($html)
            ->select('.export', 0)
            ->windowSize($width, $height)
            ->paperSize($width, $height, 'px')
            ->landscape()
            ->margins(0,0,0,0)
            ->waitUntilNetworkIdle()
            ->savePdf('report.pdf');
    }
}
