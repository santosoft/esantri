<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SantriResource\Pages;
use App\Filament\Resources\SantriResource\RelationManagers;
use App\Models\Group;
use App\Models\Muhaffizh;
use App\Models\Santri;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SantriResource extends Resource
{
    protected static ?string $model = Santri::class;
    protected static ?string $modelLabel = 'Santri';

    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Santri';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('no_induk')->required()->maxLength(16),
                Forms\Components\TextInput::make('nama')->required()->maxLength(64),
                Forms\Components\Select::make('group_id')
                    ->options(Group::all()->pluck('nama', 'id'))
                    ->preload(10)->searchable(),
                Forms\Components\Select::make('muhaffizh_id')
                    ->options(Muhaffizh::all()->pluck('nama', 'id'))
                    ->preload(10)->searchable(),
                Forms\Components\Select::make('user_id')->label('User Account')
                    // ->options(User::where('role','Santri')->pluck('email', 'id'))
                    ->relationship('user', 'email')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')->required(),
                        Forms\Components\TextInput::make('username')->required()->unique(),
                        Forms\Components\TextInput::make('email')->required()->email()->unique(),
                        Forms\Components\TextInput::make('password')->required()->password(),
                        Forms\Components\Hidden::make('role')->default('Santri'),
                    ])
                    ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                        return $action->modalHeading('Buat Akun')->modalWidth('md');
                    }),
                Forms\Components\Textarea::make('alamat')->maxLength(255),
                Forms\Components\TextInput::make('tempat_lahir')->maxLength(64),
                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->minDate(now()->subYears(90))
                    ->maxDate(now()->subYears(2)),
                Forms\Components\Select::make('gender')->required()
                    ->options(array_combine($a =['Laki', 'Perempuan'], $a)),
                Forms\Components\TextInput::make('nama_ayah')->maxLength(64),
                Forms\Components\TextInput::make('nama_ibu')->maxLength(64),
                Forms\Components\TextInput::make('no_hp')->tel()->maxLength(16),
                Forms\Components\DatePicker::make('mulai_belajar'),
                Forms\Components\TextInput::make('angkatan_kelas')->maxLength(32),
                Forms\Components\Select::make('grade')
                    ->options(array_combine($a = ['A','B','C','D'], $a)),
                Forms\Components\TextInput::make('level_santri')
                    ->numeric()->minValue(0)->maxValue(6),
                Forms\Components\FileUpload::make('foto')
                    ->directory('foto_santri')
                    ->image()->imageEditor()
                    ->imageEditorAspectRatios([ null,'1:1','3:4','16:9','4:3']),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\ImageColumn::make('foto')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('no_induk')->searchable(),
                Tables\Columns\TextColumn::make('nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('group.nama')->searchable(),
                Tables\Columns\TextColumn::make('muhaffizh.nama')->searchable(),
                Tables\Columns\TextColumn::make('user.email'),
                Tables\Columns\TextColumn::make('alamat')->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tempat_lahir')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tanggal_lahir')->date()->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\TextColumn::make('nama_ayah')->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nama_ibu')->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('no_hp'),
                Tables\Columns\TextColumn::make('mulai_belajar')->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('angkatan_kelas'),
                Tables\Columns\TextColumn::make('grade'),
                Tables\Columns\TextColumn::make('level_santri')->label('Level'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('Gender')
                    ->options(array_combine($a =['Laki', 'Perempuan'], $a)),
                Tables\Filters\SelectFilter::make('Muhaffizh')
                    ->relationship('muhaffizh', 'nama'),
                Tables\Filters\SelectFilter::make('Group')
                    ->relationship('group', 'nama'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                static::getActionRapor(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ], position: ActionsPosition::AfterColumns)
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
            'index' => Pages\ManageSantris::route('/'),
            'rapor' => Pages\RaporSantri::route('/{record}/rapor/{periode?}/{pekan?}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getActionRapor() {
        return Tables\Actions\Action::make('raporSantri')->label('Rapor')
            ->form([
                Forms\Components\Grid::make()->schema([
                    Forms\Components\DatePicker::make('periode')->native(false)
                        ->maxDate(now())->required()
                        ->format('Y-m')->displayFormat('F-Y'),
                        // ->afterStateHydrated(fn ($state, Santri $santri) => data_set($santri,'periodeRapor',$state)),
                    Forms\Components\Select::make('pekan')->required()
                        ->options([1 => 1, 2, 3, 4, 5]),
                        // ->afterStateHydrated(fn ($state, Santri $santri) => data_set($santri,'pekanRapor',$state)),
                ])->columns(2)
            ])->fillForm([
                'periode' => now(),
                'pekan' => date('W') - date('W', mktime(0,0,0,date('n'),0,date('Y'))),
            ])
            ->icon('heroicon-o-newspaper')->color('gray')
            ->modalHeading('Rapor Santri')
            ->modalWidth('sm')
            ->modalSubmitActionLabel('Lihat')
            // ->modalSubmitAction(false)
            /* ->extraModalFooterActions(function (Santri $santri): array {
                return [
                Tables\Actions\Action::make('lihatRapor')
                    ->modalHeading('Rapor Santri '.$santri->nama)
                    ->modalContent(
                        // view('filament.resources.santri-resource.pages.rapor-santri')->with(['santri'=>$santri])
                        view('livewire.rapor-santri')->with(['santri'=>$santri,'data'=>[]])
                    ),
                ];
            }) */
            ->action(function (array $data, Santri $santri): void {
                // data_set($santri,'periodeRapor',$data['periode']);
                // data_set($santri,'pekanRapor',$data['pekan']);
                redirect("santris/{$santri->id}/rapor/{$data['periode']}/{$data['pekan']}")
                    ->with([
                        'periode'=>$data['periode'],
                        'pekan'=>$data['pekan'],
                    ]);
                // $action->modalContent(view('livewire.rapor-santri')->with(['data'=>$data, 'santri'=>$santri]));
            });
    }
}
