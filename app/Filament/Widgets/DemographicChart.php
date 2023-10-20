<?php

namespace App\Filament\Widgets;

use App\Models\Unit;
use Filament\Widgets\ChartWidget;

class DemographicChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Santri';
    protected static ?string $pollingInterval = null;
    protected static ?int $sort = 3;
    protected static ?string $maxHeight = '160px';
    protected static string $color = 'warning';
    protected static ?array $options = [
        'scales' => [
            'x' => [ 'display' => false ],
            'y' => [ 'display' => false ],
        ],
    ];

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Santri',
                    'data' => array_map(fn ($n)=>rand(4,20), range(1,4)),
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(205, 86, 255)',
                    ],
                    'showLine' => false,
                ],
            ],
            'labels' => Unit::all()->pluck('nama')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
