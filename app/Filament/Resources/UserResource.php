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

/**
 * Clase que gestiona el recurso de Usuarios en el panel administrativo de Filament.
 * Proporciona funcionalidades CRUD (Crear, Leer, Actualizar, Eliminar) para los usuarios del sistema.
 */
class UserResource extends Resource
{
    /**
     * Define el modelo asociado al recurso
     */
    protected static ?string $model = User::class;

    /**
     * Define el ícono de navegación para el recurso en el panel lateral
     */
    protected static ?string $navigationIcon = 'heroicon-o-user';

    /**
     * Define el orden de aparición en el menú de navegación
     */
    protected static ?int $navigationSort = 1;

    /**
     * Define el formulario para crear y editar usuarios
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Sección principal con la información básica del usuario
                Section::make('Información del usuario')
                    ->description(fn (string $context): string =>
                        $context === 'create'
                            ? 'Complete los campos obligatorios para crear un nuevo usuario.'
                            : 'Diligencie unicamente los campos que desea modificar.'
                    )
                    ->columns(2)
                    ->schema([
                        // Campo para el nombre completo del usuario
                        TextInput::make('name')
                            ->label('Nombre completo')
                            ->required()
                            ->maxLength(255),
                        // Campo para el correo electrónico con validación de unicidad
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
                        // Selector de rol del usuario
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
                        // Interruptor para activar/desactivar el usuario
                        Toggle::make('status')
                            ->label('¿Está activo?')
                            ->required()
                    ]),
                // Sección para gestionar la contraseña del usuario
                Section::make('Contraseña')
                    ->description(fn (string $context): string =>
                        $context === 'create'
                            ? 'Complete los campos obligatorios para crear un nuevo usuario.'
                            : 'Deje en blanco si no desea cambiar la contraseña.'
                    )
                    ->columns(2)
                    ->schema([
                        // Campo para la contraseña
                        TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->rules(['confirmed']),
                        // Campo para confirmar la contraseña
                        TextInput::make('password_confirmation')
                            ->label('Confirmar contraseña')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->same('password')
                    ]),
                
            ]);

    }

    /**
     * Define la estructura y comportamiento de la tabla de usuarios
     * Incluye columnas, filtros y acciones disponibles
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Columna para mostrar el nombre del usuario
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                // Columna para mostrar el correo electrónico
                TextColumn::make('email')
                    ->searchable(),
                // Columna para mostrar el rol del usuario
                TextColumn::make('role')
                    ->label('Rol')
                    ->searchable(),
                // Columna que muestra el estado del usuario con un ícono
                IconColumn::make('status')
                    ->label('Estado')
                    ->boolean(),
            ])
            ->filters([
                // Filtro para filtrar usuarios por estado
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        true => 'Activo',
                        false => 'Inactivo'
                    ])
            ])
            ->actions([
                // Acción para editar un usuario individual
                EditAction::make()
                    ->successNotificationTitle('Usuario actualizado'),
            ])
            ->bulkActions([
                // Grupo de acciones masivas
                BulkActionGroup::make([
                    // Acción para eliminar múltiples usuarios
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Define las relaciones disponibles para el recurso de usuarios
     * @return array
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * Define las páginas disponibles para el recurso de usuarios
     * Incluye listado, creación y edición
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
