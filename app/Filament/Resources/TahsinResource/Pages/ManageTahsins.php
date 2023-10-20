<?php

namespace App\Filament\Resources\TahsinResource\Pages;

use App\Filament\Resources\TahsinResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTahsins extends ManageRecords
{
    protected static string $resource = TahsinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
