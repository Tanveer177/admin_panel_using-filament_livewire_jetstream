<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
// use Illuminate\Support\Carbon;
use Carbon\Carbon;
use App\Models\User;


class UserChartWidget  extends ChartWidget
{
    protected static ?string $heading = 'User Registration';
    protected static ?int $sort = 1;

    protected function getData(): array
    {
        return [
            'labels' => ['Daily Users', 'Weekly Users', 'Monthly Users'],
            'datasets' => [
                [
                    'label' => 'User Count',
                    'data' => [
                        User::whereDate('created_at', Carbon::today())->count(),
                        User::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
                        User::whereMonth('created_at', Carbon::now()->month)->count(),
                    ],
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(75, 192, 192)',
                        'rgb(255, 205, 86)',
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'polarArea';
    }
}
