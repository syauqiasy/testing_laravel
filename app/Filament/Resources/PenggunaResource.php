<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenggunaResource\Pages;
use App\Filament\Resources\PenggunaResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Forms\Components\Password;
use Filament\Resources\Pages\CreateRecord;

class PenggunaResource extends Resource
{
    protected static ?string $model = User::class;

    // menu navigasi
    protected static ?string $navigationIcon = 'heroicon-o-user-group'; //icon

    protected static ?string $navigationGroup = 'Pengaturan'; //nama group

    protected static ?int $navigationSort = 1; //urutan

    protected static ?string $slug = 'pengguna'; //URL

    protected static ?string $label = 'Pengguna'; //nama menu
    // end navigasi

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make()
            ->columns([
                'sm' => 1,
                'xl' => 2,
                '2xl' => 5,
            ])
            ->schema(function ($livewire) {
                    // Mengecek apakah objek adalah instance dari CreateRecord
                if ($livewire instanceof CreateRecord) {
                    return self::createSchema();
                }

                return self::editSchema();
            }),
        ]);
    }

    protected static function createSchema(): array
    {
        return [
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('email')->email()->required()->maxLength(255),
            TextInput::make('password')
            ->label('Password')
            ->password()
            ->required()
            ->maxLength(255)
            ->dehydrateStateUsing(fn ($state) => bcrypt($state)),
            TextInput::make('password_confirmation')
            ->password()
            ->required()
            ->same('password')
            ->label('Confirm Password'),
            Select::make('roles' , 'Grup')
            ->relationship('roles', 'name'),
        ];
    }

    protected static function editSchema(): array
    {
        return [
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('email')->email()->required()->maxLength(255),
            TextInput::make('password')
            ->password()
            ->maxLength(255)
            ->dehydrateStateUsing(function ($state, $livewire) {
                if ($state) {
                    return bcrypt($state);
                }
                return $livewire->getRecord()->password;
            })
            ->label('New Password'),
            TextInput::make('password_confirmation')
            ->password()
            ->same('password')
            ->label('Confirm New Password'),
            Select::make('roles', 'Grup')
            ->relationship('roles', 'name'),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label('Nama')->sortable()->searchable(),
            TextColumn::make('email')->sortable()->searchable(),
            TextColumn::make('peran')
            ->label('Grup')
            ->badge()
            ->getStateUsing(function ($record) {
                return $record->roles->pluck('name');
            }),
        ])->actions([
            EditAction::make(),
            DeleteAction::make(),
        ])
        ->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengguna::route('/'),
            'create' => Pages\CreatePengguna::route('/create'),
            'edit' => Pages\EditPengguna::route('/{record}/edit'),
        ];
    }
}
