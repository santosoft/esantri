<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MuhaffizhResource\Pages;
use App\Filament\Resources\MuhaffizhResource\RelationManagers;
use App\Models\Group;
use App\Models\Muhaffizh;
use App\Models\Unit;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MuhaffizhResource extends Resource
{
    protected static ?string $model = Muhaffizh::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Muhaffizh';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nomor_induk')
                    ->maxLength(16),
                Forms\Components\TextInput::make('nama')
                    ->maxLength(64),
                Forms\Components\Select::make('unit_id')->label('Unit')
                    ->options(Unit::all()->pluck('nama', 'id'))
                    ->preload(10)->searchable(),
                Forms\Components\Select::make('group_id')->label('Group')
                    ->options(Group::all()->pluck('nama', 'id'))
                    ->preload(10)->searchable(),
                Forms\Components\Select::make('user_id')->label('Akun User')
                    ->relationship('user', 'email')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')->required(),
                        Forms\Components\TextInput::make('username')->required()->unique(),
                        Forms\Components\TextInput::make('email')->required()->email()->unique(),
                        Forms\Components\TextInput::make('password')->required()->password(),
                        Forms\Components\Hidden::make('role')->default('Muhaffizh'),
                    ])
                    ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                        return $action->modalHeading('Buat Akun')->modalWidth('md');
                    }),
                Forms\Components\Textarea::make('alamat')->maxLength(255),
                Forms\Components\TextInput::make('tempat_lahir')->maxLength(64),
                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->minDate(now()->subYears(90))
                    ->maxDate(now()->subYears(10)),
                Forms\Components\TextInput::make('no_hp')->tel()->maxLength(16),
                Forms\Components\TextInput::make('pendidikan_terakhir')->maxLength(64),
                Forms\Components\DatePicker::make('mulai_bertugas')->maxDate(now()),
                Forms\Components\TextInput::make('angkatan_kelas')->maxLength(32),
                Forms\Components\Toggle::make('aktif'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('no_induk')->searchable(),
                Tables\Columns\TextColumn::make('nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('group.nama')->searchable(),
                Tables\Columns\TextColumn::make('unit.nama')->searchable(),
                Tables\Columns\TextColumn::make('user.email')->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('alamat')->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tempat_lahir')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tanggal_lahir')->date()->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('no_hp')->searchable(),
                Tables\Columns\TextColumn::make('pendidikan_terakhir')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('mulai_bertugas')->date()->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('angkatan_kelas'),
                Tables\Columns\IconColumn::make('aktif')
                    ->icon(fn (string $state): string => match ($state) {
                        '1' => 'heroicon-o-check-circle', default => 'heroicon-o-x-mark'
                    })
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success', default => 'gray'
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('Group')
                    ->relationship('group', 'nama'),
                Tables\Filters\SelectFilter::make('Unit')
                    ->relationship('unit', 'nama'),
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
            'index' => Pages\ManageMuhaffizhs::route('/'),
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
