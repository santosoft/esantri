<?php

namespace App\Filament\Resources\PekanResource\Pages;

use App\Filament\Resources\PekanResource;
use App\Models\Pekan;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Validation\ValidationException;

class ManagePekans extends ManageRecords
{
    protected static string $resource = PekanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->mutateFormDataUsing(function (array $data): array {
                $data['id'] = $data['tahun'].sprintf("%02d", $data['bulan']).$data['pekan'];
                return $data;
            })
            ->afterFormValidated(function (array $data) {
                if(Pekan::find($data['id'])) {
                    throw ValidationException::withMessages([
                        'mountedActionsData.0.pekan'=>"Pekan {$data['id']} sudah ada sebelumnya"
                    ]);
                }
            }),
        ];
    }
}
