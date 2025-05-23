<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ColaboratorResource\Pages;
use App\Filament\Resources\ColaboratorResource\RelationManagers;
use App\Models\Colaborator;
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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ColaboratorResource extends Resource
{
    protected static ?string $model = Colaborator::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form  
        
            ->schema([
                Section::make('Datos personales')
                
                    ->schema([
                        // Seccion: Datos personales de la persona
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Section::make()
                                    ->schema([
                                        Grid::make(1)
                                            ->columns([
                                                'default' => 1,
                                                'sm' => 2,
                                                'md' => 4,
                                                'lg' => 4,
                                            ])
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

                                                TextInput::make('personal_email')
                                                    ->label('Correo electrónico personal')
                                                    ->email()
                                                    ->required()
                                                    ->columnSpan(2)
                                                    ->maxLength(255),


                                                TextInput::make('mobile')
                                                    ->label('Número de teléfono móvil')
                                                    ->numeric()
                                                    ->maxLength(20)
                                                    ->required(),

                                                TextInput::make('phone')
                                                    ->label('Número de teléfono fijo')
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

                                                Select::make('education_level')
                                                    ->label('Nivel educativo')
                                                    ->options([
                                                        'Bachiller' => 'Bachiller',
                                                        'Tecnico' => 'Tecnico',
                                                        'Tecnologo' => 'Tecnologo',
                                                        'Profesional' => 'Profesional',
                                                    ])
                                                    ->required(),

                                                Forms\Components\Placeholder::make('')
                                                ->extraAttributes(['class' => 'text-sm text-gray-500 italic'])
                                                    ->content('Recuerda llenar todos los campos obligatorios marcados con * antes de crear el colaborador.')
                                                    ->columnSpan(3)


                                            ]),
                                    ])
                                    ->columnSpan(['lg' => 3]),

                                // Documentos
                                Forms\Components\Section::make('Documentos')
                                    ->schema([
                                        FileUpload::make('eps')
                                            ->label('EPS')
                                            ->required()
                                            ->extraAttributes(['class' => 'min-h-[42px] py-8 w-[250px]']),

                                        FileUpload::make('arl')
                                            ->label('ARL')
                                            ->required()
                                            ->extraAttributes(['class' => 'min-h-[42px] py-8 w-[250px]']),
                                    ])
                                    ->columnSpan(['lg' => 1]),
                            ])
                            ->columns(4),

                            // Seccion: Datos laborales de la persona
                            Section::make('Datos de laborales')
                                ->schema([
                                    Grid::make(1)
                                            ->columns([
                                                'default' => 1,
                                                'sm' => 2,
                                                'md' => 2,
                                                'lg' => 2,
                                            ])
                                            ->schema([
                                    

                                                TextInput::make('corporate_email')
                                                    ->label('Correo electrónico corporativo')
                                                    ->email()
                                                    ->maxLength(255),
                                                
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
                                                    ->required()
                                            ])
                    
            
                                        ])
                                    ])
                        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                ->label('Nombres')
                ->searchable(),

                Tables\Columns\TextColumn::make('last_name')
                ->label('Apellidos')
                ->searchable(),

                Tables\Columns\TextColumn::make('document_number')
                ->label('Número de documento')
                ->searchable(),

                Tables\Columns\TextColumn::make('gender')
                ->label('Género')
                ->searchable(),

                Tables\Columns\TextColumn::make('birth_date')
                ->label('Fecha de nacimiento')
                ->searchable(),

                Tables\Columns\TextColumn::make('personal_email')
                ->label('Correo electrónico personal')
                ->searchable(),

                Tables\Columns\TextColumn::make('corporate_email')
                ->label('Correo electrónico corporativo')
                ->searchable(),

                Tables\Columns\TextColumn::make('mobile')
                ->label('Número de teléfono móvil')
                ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                ->label('Número de teléfono fijo')
                ->searchable(),

                Tables\Columns\TextColumn::make('address')
                ->label('Dirección')
                ->searchable(),

                Tables\Columns\TextColumn::make('residential_city')
                ->label('Ciudad de residencia')
                ->searchable(),

                Tables\Columns\TextColumn::make('education_level')
                ->label('Nivel educativo')
                ->searchable(),

                Tables\Columns\TextColumn::make('job_position')
                ->label('Cargo')
                ->searchable(),

                Tables\Columns\TextColumn::make('hire_date')
                ->label('Fecha de contratación')
                ->searchable(),

                Tables\Columns\TextColumn::make('eps')
                ->label('EPS')
                ->searchable(),

                Tables\Columns\TextColumn::make('arl')
                ->label('ARL')
                ->searchable(),

                Tables\Columns\TextColumn::make('notes')
                ->label('Observaciones')
                ->searchable(),

                Tables\Columns\TextColumn::make('status')
                ->label('Activo')
                ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])
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
            'index' => Pages\ListColaborators::route('/'),
            'create' => Pages\CreateColaborator::route('/create'),
            'edit' => Pages\EditColaborator::route('/{record}/edit'),
        ];
    }
}
