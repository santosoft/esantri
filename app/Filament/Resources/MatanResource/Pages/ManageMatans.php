<?php

namespace App\Filament\Resources\MatanResource\Pages;

use App\Filament\Resources\MatanResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMatans extends ManageRecords
{
    protected static string $resource = MatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
