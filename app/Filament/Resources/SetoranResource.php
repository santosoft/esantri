<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SetoranResource\Pages;
use App\Filament\Resources\SetoranResource\RelationManagers;
use App\Models\Absen;
use App\Models\Group;
use App\Models\Muhaffizh;
use App\Models\Pekan;
use App\Models\Santri;
use App\Models\Setoran;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SetoranResource extends Resource
{
    protected static ?string $model = Setoran::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';
    protected static ?string $navigationGroup = 'Jurnal';
    protected static ?string $navigationLabel = 'Setoran';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        $thisWeek = date('Ym').(date('W') - date('W', mktime(0,0,0,date('n'),0,date('Y'))));
        $defaultPekanId = Pekan::find($thisWeek)?->id;
        return $form
            ->schema([
                Forms\Components\Section::make()->compact()->schema([
                    Forms\Components\Select::make('santri_id')->label('Santri')
                        ->options(Santri::all()->pluck('nama', 'id'))
                        ->preload(10)->searchable()->reactive()->required()
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            if($santri = Santri::find($state)) {
                                $set('level_santri', $santri->level_santri);
                                if($group = $santri->group) {
                                    $set('group_id', $group->id);
                                    $set('muhaffizh_id', $group->muhaffizh_id);
                                    $set('unit_id', $group->unit_id);
                                }
                            } else {
                                $set('level_santri', '');
                                $set('group_id', '');
                                $set('muhaffizh_id', '');
                                $set('unit_id', '');
                            }
                        })->columnSpan(2),
                    Forms\Components\Select::make('group_id')->label('Group')
                        ->options(Group::all()->pluck('nama', 'id'))
                        ->preload(10)->searchable()->reactive()->required()
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            $group = Group::find($state);
                            $set('muhaffizh_id', $group->muhaffizh_id);
                            $set('unit_id', $group->unit_id);
                        }),
                    Forms\Components\Select::make('pekan_id')->label('Pekan')
                        ->relationship(name: 'pekan', titleAttribute: 'id')
                        ->preload(10)->searchable()->required()->default($defaultPekanId),
                    Forms\Components\Select::make('muhaffizh_id')->label('Muhaffizh')
                        ->options(Muhaffizh::all()->pluck('nama', 'id'))
                        ->preload(10)->searchable()->columnSpan(2),
                    Forms\Components\Select::make('unit_id')->label('Unit')
                        ->options(Unit::all()->pluck('nama', 'id'))
                        ->preload(10)->searchable(),
                    Forms\Components\TextInput::make('level_santri')
                        ->numeric()->minValue(0)->maxValue(6)
                        ->extraInputAttributes(['class'=>'text-right']),
                ])->columns(4),
                Tabs::make()->tabs([
                    Tabs\Tab::make('Pencapaian')->icon('heroicon-m-book-open')->schema([
                        Forms\Components\Fieldset::make('Tahfizh')->schema([
                            Forms\Components\TextInput::make('tahfizh_juz')
                                ->suffix('Juz')->hiddenLabel()
                                ->numeric()->minValue(0)->maxValue(30)
                                ->extraInputAttributes(['class'=>'text-right']),
                            Forms\Components\TextInput::make('tahfizh_halaman')
                                ->suffix('Halaman')->hiddenLabel()
                                ->numeric()->minValue(0)->maxValue(999)
                                ->extraInputAttributes(['class'=>'text-right']),
                            Forms\Components\TextInput::make('total_tahfizh')
                                ->label('Total')->placeholder('cth: 2 Juz 8 Hal.'),
                            Forms\Components\TextInput::make('tahfizh_posisi_terakhir')
                                ->label('Posisi Terakhir')->placeholder('cth: Juz 11 Hal.71'),
                        ])->columnSpan(2),
                        Forms\Components\Fieldset::make('Tahsin')->schema([
                            Forms\Components\TextInput::make('tahsin_capaian')->label('Capaian')
                                ->numeric()->minValue(0)->maxValue(999)
                                ->extraInputAttributes(['class'=>'text-right']),
                            Forms\Components\TextInput::make('tahsin_posisi_terakhir')
                                ->label('Posisi Terakhir')->placeholder('cth: P2 13 Halaman'),
                        ])->columnSpan(2),
                        Forms\Components\Fieldset::make('Mutqin')->schema([
                            Forms\Components\TextInput::make('mutqin_halaman')
                                ->suffix('Halaman')->hiddenLabel()->numeric()->minValue(0)
                                ->extraInputAttributes(['class'=>'text-right'])
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('total_mutqin')
                                ->placeholder('cth: 4 Juz 15 Hal.')->columnSpan(2)
                                ->prefix('Total')->hiddenLabel()->maxLength(255),
                        ])->columns(3)->columnSpan(3),
                        Forms\Components\Fieldset::make('Matan')->schema([
                            Forms\Components\TextInput::make('matan_jazari')
                                ->hiddenLabel()->columnSpanFull()
                                ->placeholder('cth: Bait 12'),
                        ])->columnSpan(1),
                    ])->columns(4),
                    Tabs\Tab::make('Kehadiran')->icon('heroicon-o-clipboard-document-check')->schema([
                        Forms\Components\TextInput::make('absen_hadir')->prefix('Hadir')
                            ->numeric()->minValue(0)->maxValue(30)->hiddenLabel()
                            ->extraInputAttributes(['class'=>'text-right']),
                        Forms\Components\TextInput::make('absen_izin')->prefix('Izin')
                            ->numeric()->minValue(0)->maxValue(30)->hiddenLabel()
                            ->extraInputAttributes(['class'=>'text-right']),
                        Forms\Components\TextInput::make('absen_sakit')->prefix('Sakit')
                            ->numeric()->minValue(0)->maxValue(30)->hiddenLabel()
                            ->extraInputAttributes(['class'=>'text-right']),
                        Forms\Components\TextInput::make('absen_alpha')->prefix('Alpha')
                            ->numeric()->minValue(0)->maxValue(30)->hiddenLabel()
                            ->extraInputAttributes(['class'=>'text-right']),
                    ])->columns(2),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pekan.periode')->label('Periode')->sortable(),
                Tables\Columns\TextColumn::make('pekan.pekan')->sortable()->alignRight(),
                Tables\Columns\TextColumn::make('santri.nama')->sortable(),
                Tables\Columns\TextColumn::make('muhaffizh.nama')->sortable(),
                Tables\Columns\TextColumn::make('unit.nama')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('group.nama')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('level_santri')
                    ->label('Level')->alignRight()->sortable(),
                Tables\Columns\TextColumn::make('mutqin_halaman')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total_mutqin'),
                Tables\Columns\TextColumn::make('tahfizh_juz')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tahfizh_halaman')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tahfizh_posisi_terakhir')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total_tahfizh'),
                Tables\Columns\TextColumn::make('tahsin_capaian')
                    ->label('Tahsin')->alignRight(),
                Tables\Columns\TextColumn::make('tahsin_posisi_terakhir')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('matan_jazari'),
                Tables\Columns\TextColumn::make('absen_hadir')->label('Hadir'),
                Tables\Columns\TextColumn::make('absen_izin')->label('Izin')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('absen_sakit')->label('Sakit')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('absen_alpha')->label('Alpha')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('pekan_id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('Group')->multiple()
                    ->relationship('group', 'nama')->preload(10),
                Tables\Filters\SelectFilter::make('Unit')->multiple()
                    ->relationship('unit', 'nama'),
                Tables\Filters\SelectFilter::make('Muhaffizh')->multiple()
                    ->relationship('muhaffizh', 'nama')->preload(10),
                Tables\Filters\SelectFilter::make('Santri')->multiple()
                    ->relationship('santri', 'nama'),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSetorans::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
