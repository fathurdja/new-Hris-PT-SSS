<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TopDiligentEmployees extends ChartWidget
{
    protected static ?string $heading = 'Karyawan Terajin (Bulan Ini)';

    protected function getData(): array
    {
        $data = Attendance::select('employee_id', DB::raw('count(*) as total'))
            ->whereMonth('check_in_time', Carbon::now()->month)
            ->groupBy('employee_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Kehadiran',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#003049',
                ],
            ],
            'labels' => $data->map(fn ($row) => $row->employee->name)->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
