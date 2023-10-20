<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class TahfizhJuzChart extends ChartWidget
{
    protected static ?string $heading = 'Total Juz Tahfizh per pekan';
    protected static ?string $pollingInterval = null;
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;
    protected static ?string $maxHeight = '240px';
    protected static string $color = 'success';
    protected static ?array $options = [
        'scales' => [
            'y' => [ 'beginAtZero' => true ]
        ],
        'plugins' => [
            'legend' => [ 'display' => false ],
        ],
    ];

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Juz',
                    'data' => array_map(fn ($n)=>rand(5,20), range(1,9)),
                ],
            ],
            'labels' => ['Aug-1', 'Aug-2', 'Aug-3', 'Aug-4', 'Sep-1', 'Sep-2', 'Sep-3', 'Sep-4', 'Okt-1'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
