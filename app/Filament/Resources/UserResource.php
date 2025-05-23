<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información del usuario')
                    ->description(fn (string $context): string =>
                        $context === 'create'
                            ? 'Complete los campos obligatorios para crear un nuevo usuario.'
                            : 'Diligencie unicamente los campos que desea modificar.'
                    )
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre completo')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(
                                table: 'users', 
                                column: 'email', 
                                ignorable: fn ($record) => $record)
                            ->validationMessages([
                                'unique' => "Este correo ya ha sido utilizado."
                            ]
                            )
                            ->maxLength(255),
                        Select::make('role')
                            ->label('Rol')
                            ->required()
                            ->options([
                                'admin' => 'Admin',
                                'rrhh' => 'RR.HH',
                                'auditor' => 'Auditor',
                                'supervisor' => 'Supervisor',
                                'operador' => 'Operador',
                            ]),
                        Toggle::make('status')
                            ->label('¿Está activo?')
                            ->required()
                    ]),
                Section::make('Contraseña')
                    ->description(fn (string $context): string =>
                        $context === 'create'
                            ? 'Complete los campos obligatorios para crear un nuevo usuario.'
                            : 'Deje en blanco si no desea cambiar la contraseña.'
                    )
                    ->columns(2)
                    ->schema([
                        TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->rules(['confirmed']),
                        TextInput::make('password_confirmation')
                            ->label('Confirmar contraseña')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->same('password')
                    ]),
                
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('role')
                    ->label('Rol')
                    ->searchable(),
                IconColumn::make('status')
                    ->label('Estado')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        true => 'Activo',
                        false => 'Inactivo'
                    ])
            ])
            ->actions([
                EditAction::make()
                    ->successNotificationTitle('Usuario actualizado'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
