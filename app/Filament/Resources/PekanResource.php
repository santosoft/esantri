<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PekanResource\Pages;
use App\Models\Pekan;
use Carbon\Carbon;
use DateTime;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\ValidationException;

class PekanResource extends Resource
{
    protected static ?string $model = Pekan::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Master';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tahun')->prefix('Tahun')
                    ->options(array_combine($a = [date('Y'), date('Y')-1], $a))
                    ->default(date('Y'))->hiddenLabel()->disabledOn('edit')
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        $tgl = static::getTglAwalAkhirPekan($state, $get('bulan'), $get('pekan'));
                        $set('tgl_awal', $tgl[0]);
                        $set('tgl_akhir', $tgl[1]);
                    })->live(onBlur: true),
                Forms\Components\Select::make('bulan')->prefix('Bulan')
                    ->options(array_combine($keys = range(1,12), array_map(fn ($n) => date('F', strtotime("2023-$n-15")), $keys)))
                    ->default(date('n'))->hiddenLabel()->disabledOn('edit')
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        $tgl = static::getTglAwalAkhirPekan($get('tahun'), $state, $get('pekan'));
                        $set('tgl_awal', $tgl[0]);
                        $set('tgl_akhir', $tgl[1]);
                    })->live(onBlur: true),
                Forms\Components\TextInput::make('pekan')->prefix('Pekan')
                    ->numeric()->minValue(1)->maxValue(5)->hiddenLabel()->disabledOn('edit')
                    ->default((date('W') - date('W', mktime(0,0,0,date('n'),0,date('Y')))))
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        $tgl = static::getTglAwalAkhirPekan($get('tahun'), $get('bulan'), $state);
                        $set('tgl_awal', $tgl[0]);
                        $set('tgl_akhir', $tgl[1]);
                    })->live(onBlur: true),
                Forms\Components\DatePicker::make('tgl_awal')->prefix('Awal')
                    ->default(Carbon::now()->startOfWeek())->hiddenLabel()->required(),
                Forms\Components\DatePicker::make('tgl_akhir')->prefix('Akhir')
                    ->default(Carbon::now()->endOfWeek()->subDays(2))->hiddenLabel()->required(),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('tahun'),
                Tables\Columns\TextColumn::make('bulan'),
                Tables\Columns\TextColumn::make('pekan'),
                Tables\Columns\TextColumn::make('tgl_awal')->date(),
                Tables\Columns\TextColumn::make('tgl_akhir')->date(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['id'] = $data['tahun'].sprintf("%02d", $data['bulan']).$data['pekan'];
                    return $data;
                }),
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
            'index' => Pages\ManagePekans::route('/'),
        ];
    }

    public static function getTglAwalAkhirPekan($tahun, $bulan, $pekan): array
    {
        $week = date('W', mktime(0,0,0,$bulan,0,$tahun)) + $pekan;
        $tgl = new DateTime();
        $tgl->setIsoDate($tahun, $week);
        return [$tgl->format('Y-m-d'), $tgl->modify('+4 days')->format('Y-m-d')];
    }
}
