<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TahfizhResource\Pages;
use App\Filament\Resources\TahfizhResource\RelationManagers;
use App\Models\Group;
use App\Models\Muhaffizh;
use App\Models\Santri;
use App\Models\Tahfizh;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TahfizhResource extends Resource
{
    protected static ?string $model = Tahfizh::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';
    protected static ?string $navigationGroup = 'Setoran';
    protected static ?int $navigationSort = 14;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('santri_id')->label('Santri')
                    ->options(Santri::all()->pluck('nama', 'id'))
                    ->preload(10)->searchable(),
                Forms\Components\Select::make('group_id')->label('Group')
                    ->options(Group::all()->pluck('nama', 'id'))
                    ->preload(10)->searchable()->reactive()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        $group = Group::find($state);
                        $set('muhaffizh_id', $group->muhaffizh_id);
                        $set('unit_id', $group->unit_id);
                    }),
                Forms\Components\Select::make('muhaffizh_id')->label('Muhaffizh')
                    ->options(Muhaffizh::all()->pluck('nama', 'id'))
                    ->preload(10)->searchable(),
                Forms\Components\Select::make('unit_id')->label('Unit')
                    ->options(Unit::all()->pluck('nama', 'id'))
                    ->preload(10)->searchable(),
                Forms\Components\Section::make('Periode')->schema([
                    Forms\Components\Select::make('tahun')->prefix('Tahun')
                        ->options(array_combine($a = [date('Y'), date('Y')-1], $a))
                        ->default(date('Y'))->hiddenLabel(),
                    Forms\Components\Select::make('bulan')->prefix('Bulan')
                        ->options(array_combine($keys = range(1,12), array_map(fn ($n) => date('F', strtotime("2023-$n-15")), $keys)))
                        ->default(date('n'))->hiddenLabel(),
                    Forms\Components\TextInput::make('pekan')->prefix('Pekan')
                        ->numeric()->minValue(1)->maxValue(5)->hiddenLabel()
                        ->default((date('W') - date('W', mktime(0,0,0,date('n'),0,date('Y'))))),
                ])->columns(3),
                Forms\Components\Section::make('Capaian')->schema([
                    Forms\Components\TextInput::make('juz')->hiddenLabel()
                        ->suffix('Juz')->numeric()->minValue(0)->maxValue(30),
                    Forms\Components\TextInput::make('halaman')->hiddenLabel()
                        ->suffix('Halaman')->numeric()->minValue(0)->maxValue(30),
                ])->columns(2),
                Forms\Components\TextInput::make('posisi_terakhir')
                    ->maxLength(128)->placeholder('cth: Juz 24 Hal.10'),
                Forms\Components\TextInput::make('total_tahfizh')
                    ->maxLength(128)->placeholder('cth: 28 Juz 12 Halaman'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('santri.nama')->sortable(),
                Tables\Columns\TextColumn::make('muhaffizh.nama')->sortable(),
                Tables\Columns\TextColumn::make('unit.nama')->sortable(),
                Tables\Columns\TextColumn::make('group.nama')->sortable(),
                Tables\Columns\TextColumn::make('periode'),
                Tables\Columns\TextColumn::make('tahun')
                    ->numeric()->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bulan')
                    ->numeric()->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pekan')->numeric(),
                Tables\Columns\TextColumn::make('juz')->numeric(),
                Tables\Columns\TextColumn::make('halaman')->numeric(),
                Tables\Columns\TextColumn::make('posisi_terakhir'),
                Tables\Columns\TextColumn::make('total_tahfizh'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('Group')->multiple()
                    ->relationship('group', 'nama'),
                Tables\Filters\SelectFilter::make('Unit')->multiple()
                    ->relationship('unit', 'nama'),
                Tables\Filters\SelectFilter::make('Muhaffizh')->multiple()
                    ->relationship('muhaffizh', 'nama'),
                Tables\Filters\SelectFilter::make('Santri')->multiple()
                    ->relationship('santri', 'nama'),
                Tables\Filters\SelectFilter::make('Tahun')->multiple()
                    ->options(array_combine($a = [date('Y'), date('Y')-1], $a))
                    ->default(date('Y')),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTahfizhs::route('/'),
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
