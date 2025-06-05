<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ColaboratorResource\Pages;
use App\Models\Colaborator;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

/**
 * Recurso para la gestión de Colaboradores en el panel administrativo
 * 
 * Esta clase maneja todas las operaciones CRUD relacionadas con los colaboradores,
 * incluyendo la gestión de sus datos personales, información de contacto y documentos.
 */
class ColaboratorResource extends Resource
{
    /**
     * El modelo asociado al recurso
     */
    protected static ?string $model = Colaborator::class;

    /**
     * El ícono que se muestra en la navegación
     */
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * Orden de aparición en el menú de navegación
     */
    protected static ?int $navigationSort = 2;

    /**
     * Define la estructura del formulario para crear y editar colaboradores
     * 
     * @param Form $form El formulario a configurar
     * @return Form El formulario configurado con todos los campos necesarios
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Identificación del colaborador')
                    ->description('Los campos marcados con * son obligatorios')
                    ->columns(2)
                    ->schema([
                        Select::make('document_type')
                            ->label('Tipo de documento')
                            ->suffixIcon('heroicon-o-identification')
                            ->required()
                            ->options([
                                'CC' => 'Cédula de ciudadanía',
                                'CE' => 'Cédula de extranjería',
                                'TI' => 'Tarjeta de identidad',
                            ]),

                        TextInput::make('document_number')
                            ->label('Número de documento')
                            ->suffixIcon('heroicon-o-identification')
                            ->required()
                            ->maxLength(20)
                            ->rule('regex:/^[0-9]{1,20}$/')
                            ->validationMessages([
                                'regex' => 'Ingrese un número de documento valido.'
                            ])
                            ->helperText('Ingrese solo números, máximo 20 caracteres.'),

                        TextInput::make('first_name')
                            ->label('Nombres completos')
                            ->suffixIcon('heroicon-o-user')
                            ->required()
                            ->dehydrateStateUsing(fn($state) => strtoupper($state))
                            ->maxLength(100),

                        TextInput::make('last_name')
                            ->label('Apellidos completos')
                            ->suffixIcon('heroicon-o-user')
                            ->required()
                            ->dehydrateStateUsing(fn($state) => strtoupper($state))
                            ->maxLength(100),

                        Select::make('gender')
                            ->label('Género')
                            ->suffixIcon('heroicon-o-identification')
                            ->required()
                            ->options([
                                'M' => 'Masculino',
                                'F' => 'Femenino',
                                'O' => 'Otro',
                            ]),

                        DatePicker::make('birth_date')
                            ->label('Fecha de nacimiento')
                            ->suffixIcon('heroicon-o-calendar')
                            ->maxDate(now())
                            ->required(),
                    ]),

                Section::make('Información de contacto')
                    ->description('Los campos marcados con * son obligatorios')
                    ->columns(2)
                    ->schema([
                        TextInput::make('personal_email')
                            ->label('Correo personal')
                            ->suffixIcon('heroicon-o-envelope')
                            ->email()
                            ->required()
                            ->dehydrateStateUsing(fn($state) => strtoupper($state))
                            ->maxLength(255),

                        TextInput::make('mobile')
                            ->label('Número móvil')
                            ->suffixIcon('heroicon-o-device-phone-mobile')
                            ->numeric()
                            ->maxLength(15)
                            ->required()
                            ->rule('regex:/^[0-9]{1,15}$/')
                            ->validationMessages([
                                'regex' => 'Ingrese un número móvil valido'
                            ])
                            ->helperText('Ingrese número móvil sin espacios ni prefijos, máximo 15 caracteres.'),

                        TextInput::make('phone')
                            ->label('Número fijo')
                            ->suffixIcon('heroicon-o-phone')
                            ->numeric()
                            ->maxLength(15)
                            ->rule('regex:/^[0-9]{1,15}$/')
                            ->validationMessages([
                                'regex' => 'Ingrese un número fijo valido'
                            ])
                            ->helperText('Ingrese un número fijo sin espacios, máximo 15 caracteres.'),

                        TextInput::make('address')
                            ->label('Dirección de residencia')
                            ->suffixIcon('heroicon-o-home')
                            ->required()
                            ->dehydrateStateUsing(fn($state) => strtoupper($state))
                            ->maxLength(150),

                        TextInput::make('residential_city')
                            ->label('Ciudad')
                            ->suffixIcon('heroicon-o-map-pin')
                            ->required()
                            ->dehydrateStateUsing(fn($state) => strtoupper($state))
                            ->maxLength(100),
                    ]),

                Section::make('Detalles laborales')
                    ->description('Los campos marcados con * son obligatorios')
                    ->columns(2)
                    ->schema([
                        TextInput::make('corporate_email')
                            ->label('Correo corporativo')
                            ->suffixIcon('heroicon-o-envelope')
                            ->email()
                            ->dehydrateStateUsing(fn($state) => strtoupper($state))
                            ->maxLength(255),

                        DatePicker::make('hire_date')
                            ->label('Fecha de contratación')
                            ->suffixIcon('heroicon-o-calendar')
                            ->maxDate(now())
                            ->required(),

                        Select::make('job_position')
                            ->label('Cargo')
                            ->suffixIcon('heroicon-o-briefcase')
                            ->required()
                            ->options([
                                'JEFE DE PROYECTO' => 'Jefe de proyecto',
                                'DESARROLLADOR' => 'Desarrollador',
                                'ANALISTA' => 'Analista',
                                'TESTER' => 'Tester',
                            ]),

                        Select::make('education_level')
                            ->label('Nivel educativo')
                            ->suffixIcon('heroicon-o-academic-cap')
                            ->required()
                            ->options([
                                'BACHILLER' => 'Bachiller',
                                'TECNICO' => 'Tecnico',
                                'TECNOLOGO' => 'Tecnologo',
                                'PROFESIONAL' => 'Profesional',
                            ]),

                        FileUpload::make('eps')
                            ->label('EPS')
                            ->acceptedFileTypes(['application/pdf'])
                            ->helperText('Cargue el archivo en formato PDF.')
                            ->required(),

                        FileUpload::make('arl')
                            ->label('ARL')
                            ->acceptedFileTypes(['application/pdf'])
                            ->helperText('Cargue el archivo en formato PDF.')
                            ->required(),

                        Toggle::make('status')
                            ->label('Activo')
                            ->inline()
                            ->onColor('success')
                            ->offColor('danger'),

                        Textarea::make('notes')
                            ->label('Observaciones')
                            ->columnSpan('full')
                            ->default('Sin observaciones.')
                            ->required(),
                    ]),
            ]);
    }

    /**
     * Define la estructura de la tabla para listar colaboradores
     * 
     * @param Table $table La tabla a configurar
     * @return Table La tabla configurada con columnas, filtros y acciones
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Nombres')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('last_name')
                    ->label('Apellidos')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('document_number')
                    ->label('Número documento')
                    ->searchable(),

                Tables\Columns\TextColumn::make('job_position')
                    ->label('Cargo')
                    ->searchable(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Activo')
                    ->trueIcon('heroicon-o-check-circle')
                    ->trueColor('success')
                    ->falseIcon('heroicon-o-x-circle')
                    ->falseColor('danger')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('job_position')
                    ->label('Cargo')
                    ->options([
                        'Jefe de proyecto' => 'Jefe de proyecto',
                        'Desarrollador' => 'Desarrollador',
                        'Analista' => 'Analista',
                        'Tester' => 'Tester',
                    ]),

                SelectFilter::make('status')
                    ->label('Activo')
                    ->options([
                        '1' => 'Activo',
                        '0' => 'Inactivo',
                    ]),
            ])
            ->actions([
                ViewAction::make()
                    ->modalHeading('Detalles del colaborador')
                    ->modalWidth('6xl'),

                Action::make('Documentos')
                    ->modalWidth('6xl')
                    ->icon('heroicon-o-document')
                    ->modalIcon('heroicon-o-document')
                    ->modalContent(function ($record) {
                        return view('components.file-modal', [
                            'epsUrl' => Storage::url($record->eps),
                            'arlUrl' => Storage::url($record->arl)
                        ]);
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])
            ]);
    }

    /**
     * Define las relaciones disponibles para el recurso
     * 
     * @return array Las relaciones configuradas para el recurso
     */
    public static function getRelations(): array
    {
        return [];
    }

    /**
     * Define las páginas disponibles para el recurso
     * 
     * @return array Las rutas configuradas para listar, crear y editar colaboradores
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListColaborators::route('/'),
            'create' => Pages\CreateColaborator::route('/create'),
            'edit' => Pages\EditColaborator::route('/{record}/edit'),
        ];
    }
}
