<?php

namespace App\Filament\Resources\MuhaffizhResource\Pages;

use App\Filament\Resources\MuhaffizhResource;
use App\Models\Muhaffizh;
use App\Models\Unit;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;

class ManageMuhaffizhs extends ManageRecords
{
    protected static string $resource = MuhaffizhResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ImportAction::make()->fields([
                ImportField::make('no_induk'),
                ImportField::make('nama'),
                ImportField::make('alamat'),
                ImportField::make('tempat_lahir'),
                ImportField::make('tanggal_lahir'),
                ImportField::make('no_hp'),
                ImportField::make('pendidikan_terakhir'),
                ImportField::make('mulai_bertugas'),
                ImportField::make('angkatan_kelas'),
                ImportField::make('aktif'),
                ImportField::make('unit'),
                ImportField::make('email')->rules('email|max:255'),
            ], columns: 3)
            ->color('gray')
            ->mutateBeforeCreate(function ($row) {
                if(array_key_exists('unit', $row)) {
                    if($unit = Unit::where('nama', 'like', trim($row['unit']).'%')->first())
                        $row['unit_id'] = $unit->id;
                    unset($row['unit']);
                }
                if(array_key_exists('email', $row)) {
                    if($user = User::where('email', 'like', trim($row['email']).'%')->first())
                        $row['user_id'] = $user->id;
                    else {
                        $username = substr($row['email'], 0, strpos($row['email'], '@'));
                        $user = User::create([
                            'name'     => $row['nama'],
                            'email'    => $row['email'],
                            'username' => $username,
                            'password' => bcrypt($username),
                            'role'     => 'Muhaffizh'
                        ]);
                        $row['user_id'] = $user->id;
                    }
                    unset($row['email']);
                }
                if(!empty($row['tanggal_lahir'])) {
                    $row['tanggal_lahir'] = static::parseExcelDate($row['tanggal_lahir']);
                }
                if(!empty($row['mulai_bertugas'])) {
                    $row['mulai_bertugas'] = static::parseExcelDate($row['mulai_bertugas']);
                }
                return $row;
            }),
            Actions\ActionGroup::make([
                Actions\Action::make('unduhTemplate')->url('docs/template_muhaffizh.xlsx'),
                Actions\Action::make('unduhContohIsian')->url('docs/template_muhaffizh_contoh.xlsx'),
            ])->color('gray'),
        ];
    }

    public static function parseExcelDate(?string $date, ?string $format = 'Y-m-d'): string {
        if(empty($date)) return '';
        if(strpos($date, '-') !== false) return $date;
        return date($format, ($date - 25569) * 86400);
    }
}
