<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MatanResource\Pages;
use App\Models\Group;
use App\Models\Matan;
use App\Models\Muhaffizh;
use App\Models\Pekan;
use App\Models\Santri;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MatanResource extends Resource
{
    protected static ?string $model = Matan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Jurnal';
    protected static ?int $navigationSort = 18;

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
                ])->columns(4)->disabled(),
                Forms\Components\TextInput::make('matan_jazari'),
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
                Tables\Columns\TextColumn::make('unit.nama')->sortable(),
                Tables\Columns\TextColumn::make('group.nama')->sortable(),
                Tables\Columns\TextColumn::make('periode'),
                Tables\Columns\TextColumn::make('tahun')
                    ->numeric()->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bulan')
                    ->numeric()->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('matan_jazari'),
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

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ManageMatans::route('/'),
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
