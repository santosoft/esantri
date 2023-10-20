<?php

namespace App\Filament\Resources\MuhaffizhResource\Pages;

use App\Filament\Resources\MuhaffizhResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMuhaffizhs extends ManageRecords
{
    protected static string $resource = MuhaffizhResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
