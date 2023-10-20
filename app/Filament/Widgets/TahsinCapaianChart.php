<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class TahsinCapaianChart extends ChartWidget
{
    protected static ?string $heading = 'Total Capaian Tahsin';
    protected static ?string $pollingInterval = null;
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '160px';
    protected static string $color = 'info';
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
                    'data' => array_map(fn ($n)=>rand(5,35), range(1,9)),
                    'fill' => 'start',
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
