<?php

namespace App\Filament\Resources\MutqinResource\Pages;

use App\Filament\Resources\MutqinResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMutqins extends ManageRecords
{
    protected static string $resource = MutqinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
