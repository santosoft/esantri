<?php

namespace App\Filament\Resources\TahfizhResource\Pages;

use App\Filament\Resources\TahfizhResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTahfizhs extends ManageRecords
{
    protected static string $resource = TahfizhResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
