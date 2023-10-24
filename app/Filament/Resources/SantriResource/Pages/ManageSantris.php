<?php

namespace App\Filament\Resources\SantriResource\Pages;

use App\Filament\Resources\SantriResource;
use App\Models\Group;
use App\Models\Muhaffizh;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;

class ManageSantris extends ManageRecords
{
    protected static string $resource = SantriResource::class;

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
                ImportField::make('gender'),
                ImportField::make('nama_ayah'),
                ImportField::make('nama_ibu'),
                ImportField::make('no_hp'),
                ImportField::make('mulai_belajar'),
                ImportField::make('angkatan_kelas'),
                ImportField::make('grade'),
                ImportField::make('level_santri'),
                ImportField::make('muhaffizh'),
                ImportField::make('group'),
                ImportField::make('email')->rules('email|max:255'),
            ], columns: 3)
            ->color('gray')
            ->mutateBeforeCreate(function ($row) {
                if(array_key_exists('muhaffizh', $row)) {
                    if($muhaffizh = Muhaffizh::where('nama', 'like', trim($row['muhaffizh']).'%')->first())
                        $row['muhaffizh_id'] = $muhaffizh->id;
                    unset($row['muhaffizh']);
                }
                if(array_key_exists('group', $row)) {
                    if($group = Group::where('nama', 'like', trim($row['group']).'%')->first())
                        $row['group_id'] = $group->id;
                    unset($row['group']);
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
                            'role'     => 'Santri'
                        ]);
                        $row['user_id'] = $user->id;
                    }
                    unset($row['email']);
                }
                if(!empty($row['tanggal_lahir'])) {
                    $row['tanggal_lahir'] = static::parseExcelDate($row['tanggal_lahir']);
                }
                if(!empty($row['mulai_belajar'])) {
                    $row['mulai_belajar'] = static::parseExcelDate($row['mulai_belajar']);
                }
                return $row;
            }),
            Actions\ActionGroup::make([
                Actions\Action::make('unduhTemplate')->url('docs/template_santri.xlsx'),
                Actions\Action::make('unduhContohIsian')->url('docs/template_santri_contoh.xlsx'),
            ])->color('gray'),
        ];
    }

    public static function parseExcelDate(?string $date, ?string $format = 'Y-m-d'): string {
        if(empty($date)) return '';
        if(strpos($date, '-') !== false) return $date;
        return date($format, ($date - 25569) * 86400);
    }
}
