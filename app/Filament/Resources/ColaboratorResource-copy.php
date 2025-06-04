<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ColaboratorResource\Pages;
use App\Filament\Resources\ColaboratorResource\RelationManagers;
use App\Models\Colaborator;
use DeepCopy\Filter\Filter;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Actions\Action;

/**
 * Clase que gestiona el recurso de Colaboradores en el panel administrativo de Filament.
 * Proporciona funcionalidades CRUD para la gestión de colaboradores, incluyendo sus datos personales y laborales,
 * así como la gestión de documentos (EPS y ARL).
 */
class ColaboratorResource extends Resource
{
    /**
     * Define el modelo asociado al recurso
     */
    protected static ?string $model = Colaborator::class;

    /**
     * Define el ícono de navegación para el recurso en el panel lateral
     */
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * Define el orden de aparición en el menú de navegación
     */
    protected static ?int $navigationSort = 2;

    /**
     * Define el formulario para crear y editar colaboradores
     * Incluye secciones para datos personales y laborales
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Datos personales')
                    ->description('Los campos marcados con * son obligatorios')
                    ->schema([
                        Select::make('document_type')
                            ->label('Tipo de documento')
                            ->options([
                                'CC' => 'Cédula de ciudadanía',
                                'CE' => 'Cédula de extranjería',
                                'TI' => 'Tarjeta de identidad',
                            ])
                            ->required()
                            ->columnSpan(2)
                            ->reactive(),
                        
                        TextInput::make('document_number')
                            ->label('Número de documento')
                            ->required()
                            ->columnSpan(2)
                            ->maxLength(10)
                            ->validationMessages([
                                'required' => 'El número de documento es obligatorio.',
                                'alpha_num' => 'El número de documento debe contener solo números y letras.',
                                'numeric' => 'El número de documento debe ser numérico.',
                            ]),

                        TextInput::make('first_name')
                            ->label('Nombres completos')
                            ->required()
                            ->columnSpan(2)
                            ->maxLength(255),

                        TextInput::make('last_name')
                            ->label('Apellidos completos')
                            ->required()
                            ->columnSpan(2)
                            ->maxLength(255),

                        Select::make('gender')
                            ->label('Género')
                             ->options([
                                'M' => 'Masculino',
                                'F' => 'Femenino',
                                'O' => 'Otro',
                            ])
                            ->required(),

                        DatePicker::make('birth_date')
                            ->label('Fecha de nacimiento')
                            ->required(),
                    ]),

                Section::make('Datos de contacto')
                    ->description('Los campos marcados con * son obligatorios')
                    ->schema([
                        TextInput::make('personal_email')
                            ->label('Correo personal')
                            ->email()
                            ->required()
                            ->columnSpan(2)
                            ->maxLength(255),

                        TextInput::make('mobile')
                            ->label('Número móvil')
                            ->numeric()
                            ->maxLength(20)
                            ->required(),

                        TextInput::make('phone')
                            ->label('Número fijo')
                            ->numeric()
                            ->maxLength(20),

                        TextInput::make('address')
                            ->label('Dirección')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('residential_city')
                            ->label('Ciudad de residencia')
                            ->required()
                            ->maxLength(255),
                    ]),

                Section::make('Formación y afiliaciones')

                        Select::make('education_level')
                            ->label('Nivel educativo')
                            ->options([
                                'Bachiller' => 'Bachiller',
                                'Tecnico' => 'Tecnico',
                                'Tecnologo' => 'Tecnologo',
                                'Profesional' => 'Profesional',
                            ])
                            ->required(),

                        FileUpload::make('eps')
                            ->label('EPS')
                            ->required()
                            ->panelAspectRatio('2:1')
                            ->extraAttributes(['class' => ' py-2']),

                        FileUpload::make('arl')
                            ->label('ARL')
                            ->required()
                            ->panelAspectRatio('2:1')
                            ->extraAttributes(['class' => ' py-2']),



                        DatePicker::make('hire_date')
                            ->label('Fecha de contratación')
                            ->required(),

                        Select::make('job_position')
                            ->label('Cargo')
                            ->options([
                                'Jefe de proyecto' => 'Jefe de proyecto',
                                'Desarrollador' => 'Desarrollador',
                                'Analista' => 'Analista',
                                'Tester' => 'Tester',
                            ])
                            ->columnSpan(2)
                            ->required(),

                        Textarea::make('notes')
                            ->label('Observaciones')
                            ->columnSpan('full')
                            ->required(),

                        Toggle::make('status')
                            ->label('Activo')
            ]);
    }

    /**
     * Define la estructura y comportamiento de la tabla de colaboradores
     * Incluye columnas, filtros y acciones disponibles
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Columna para mostrar los nombres del colaborador
                Tables\Columns\TextColumn::make('first_name')
                ->label('Nombres')
                ->sortable()
                ->searchable(),

                // Columna para mostrar los apellidos del colaborador
                Tables\Columns\TextColumn::make('last_name')
                ->label('Apellidos')
                ->sortable()
                ->searchable(),

                // Columna para mostrar el número de documento
                Tables\Columns\TextColumn::make('document_number')
                ->label('Número documento')
                ->searchable(),

                // Columna para mostrar el cargo del colaborador
                Tables\Columns\TextColumn::make('job_position')
                ->label('Cargo')
                ->searchable(),
                
                // Columna que muestra el estado del colaborador con un ícono
                Tables\Columns\IconColumn::make('status')
                ->label('Activo')
                ->trueIcon('heroicon-o-check-circle')
                ->trueColor('success')
                ->falseIcon('heroicon-o-x-circle')
                ->falseColor('danger')
                ->boolean(),
               
            ])
            ->filters([
                // Filtro para filtrar colaboradores por cargo
                SelectFilter::make('job_position')
                ->label('Cargo')
                ->options([
                    'Jefe de proyecto' => 'Jefe de proyecto',
                    'Desarrollador' => 'Desarrollador',
                    'Analista' => 'Analista',
                    'Tester' => 'Tester',
                ]),

                // Filtro para filtrar colaboradores por estado
                SelectFilter::make('status')
                    ->label('Activo')
                    ->options([
                        '1' => 'Activo',
                        '0' => 'Inactivo',
                    ]),
            ])
            ->actions([
                // Acción para ver detalles del colaborador
                ViewAction::make()
                    ->modalHeading('Detalles del colaborador')
                    ->modalWidth('6xl'),

                // Acción para ver documentos del colaborador
                Action::make('Documentos')
                    ->modalWidth('6xl')
                    ->icon('heroicon-o-document')
                    ->modalIcon('heroicon-o-document')
                    ->modalContent(function ($record){
                        return view('components.file-modal', [
                            'epsUrl' => Storage::url($record->eps),
                            'arlUrl' => Storage::url($record->arl)
                        ]);
                    }),
                
                // Acciones estándar de edición y eliminación
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Grupo de acciones masivas
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])
            ]);
    }

    /**
     * Define las relaciones disponibles para el recurso de colaboradores
     * @return array
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * Define las páginas disponibles para el recurso de colaboradores
     * Incluye listado, creación y edición
     * @return array
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
