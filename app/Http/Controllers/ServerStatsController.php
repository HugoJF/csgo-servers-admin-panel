<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServerStatsController extends Controller
{
    public function index($server_id = null, $time = null)
    {
        if ($server_id !== null) {
            $servers = Server::with('stats')->where('id', $server_id)->get();
        } else {
            $servers = Server::with('stats')->get()->toArray();
        }
        foreach ($servers as $server) {

            $data = $server['stats'];
            $hist = array();
            $lineChartDataTable = \Lava::DataTable();
            $pieChartDataTable = \Lava::DataTable();
            $historyFpsDataTable = \Lava::DataTable();

            $lineChartDataTable->addNumberColumn('svms')->addNumberColumn('measurements');
            $pieChartDataTable->addStringColumn('frameTime')->addNumberColumn('measurements');
            $historyFpsDataTable->addNumberColumn('timeAgo')->addNumberColumn('FPS');

            foreach ($data as $d) {

                $dif = (new Carbon($d['created_at']))->diffInSeconds();
                if ($dif < 120) {
                    $historyFpsDataTable->addRow([$dif, floatval($d['fps'])]);
                }

                $svms = floatval($d['svms']);

                if ($svms > 31.25) $svms = 31.25;

                $svms = intval($svms * 10);

                if (!isset($hist[$svms])) {
                    $hist[$svms] = 0;
                }

                $hist[$svms]++;
            }

            ksort($hist);

            $FT256 = 1000 / 256;
            $FT128 = 1000 / 128;
            $FT102 = 1000 / 102;
            $FT64 = 1000 / 64;

            $below256 = 0;
            $in256and128 = 0;
            $in128and102 = 0;
            $in102and64 = 0;
            $above64 = 0;

            foreach ($hist as $ka => $a) {
                $k = $ka / 10;

                if ($k <= $FT256) $below256 += $a;
                if ($k >= $FT128 && $k <= $FT256) $in256and128 += $a;
                if ($k >= $FT128 && $k < $FT102) $in128and102 += $a;
                if ($k >= $FT102 && $k < $FT64) $in102and64 += $a;
                if ($k > $FT64) $above64 += $a;


                $lineChartDataTable->addRow([$k, $a]);
            }

            $pieChartDataTable->addRow(['Above 256 FPS', $below256])
                ->addRow(['Above 128 FPS', $in256and128])
                ->addRow(['Above 102 FPS', $in128and102])
                ->addRow(['Above 64 FPS', $in102and64])
                ->addRow(['Below 64 FPS', $above64]);


            \Lava::LineChart('ServerLine-' . $server['id'], $lineChartDataTable, [
                'title' => 'Server ' . $server['id'] . ' performance chart',
                'interpolateNulls' => true,
                'smoothLine' => true,
            ]);

            \Lava::PieChart('ServerPie-' . $server['id'], $pieChartDataTable, [
                'title' => 'Server ' . $server['id'] . ' frame-time chart',
                'interpolateNulls' => true,
                'smoothLine' => true,
            ]);

            \Lava::LineChart('ServerHist-' . $server['id'], $historyFpsDataTable, [
                'title' => 'Server ' . $server['id'] . ' FPS history',
                'interpolateNulls' => true,
                'smoothLine' => true
            ]);
        }
        //return $hist;

        return view('server_stats', [
            'servers' => $servers
        ]);

    }
}
