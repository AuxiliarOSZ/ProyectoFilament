<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ColaboratorResource\Pages;
use App\Filament\Resources\ColaboratorResource\RelationManagers;
use App\Models\Colaborator;
use Filament\Forms;
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
                Forms\Components\Grid::make([
                    'default' => 1,
                    'sm' => 2,
                    'md' => 2,
                    'lg' => 3,
                ])
                ->schema([
                    Forms\Components\Select::make('document_type')
                        ->label('Tipo de documento')
                        ->options([
                            'CC' => 'Cédula de ciudadanía',
                            'CE' => 'Cédula de extranjería',
                            'TI' => 'Tarjeta de identidad',
                            //'PA' => 'Pasaporte',
                        ])
                        ->required()
                        ->reactive(),

                    Forms\Components\TextInput::make('document_number')
                        ->label('Número de documento')
                        /*->rules(function(callable $get){
                            return $get('document_type') === 'PA'
                            ? ['required', 'alpha_num']
                            : ['required', 'numeric'];
                        }) */
                        ->required()
                        ->maxLength(10)
                        ->validationMessages([
                            'required' => 'El número de documento es obligatorio.',
                            'alpha_num' => 'El número de documento debe contener solo números y letras.',
                            'numeric' => 'El número de documento debe ser numérico.',
                        ]),

                    Forms\Components\TextInput::make('first_name')
                        ->label('Nombres completos')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('last_name')
                        ->label('Apellidos completos')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Select::make('gender')
                        ->label('Género')
                        ->options([
                            'M' => 'Masculino',
                            'F' => 'Femenino',
                            'O' => 'Otro',
                        ])
                        ->required(),

                    Forms\Components\DatePicker::make('birth_date')
                        ->label('Fecha de nacimiento')
                        ->required(),

                    Forms\Components\TextInput::make('personal_email')
                        ->label('Correo electrónico personal')
                        ->email()
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('corporate_email')
                        ->label('Correo electrónico corporativo')
                        ->email()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('mobile')
                        ->label('Número de teléfono móvil')
                        ->maxLength(20)
                        ->required(),

                    Forms\Components\TextInput::make('phone')
                        ->label('Número de teléfono fijo')
                        ->maxLength(20),
                    
                    Forms\Components\TextInput::make('address')
                        ->label('Dirección')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('residential_city')
                        ->label('Ciudad de residencia')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Select::make('education_level')
                        ->label('Nivel educativo')
                        ->options([
                            'Bachiller' => 'Bachiller',
                            'Tecnico' => 'Tecnico',
                            'Tecnologo' => 'Tecnologo',
                            'Profesional' => 'Profesional',
                        ])
                        ->required(),

                    Forms\Components\Select::make('job_position')
                        ->label('Cargo')
                        ->options([
                            'Jefe de proyecto' => 'Jefe de proyecto',
                            'Desarrollador' => 'Desarrollador',
                            'Analista' => 'Analista',
                            'Tester' => 'Tester',
                        ])
                        ->required(),

                    Forms\Components\DatePicker::make('hire_date')
                        ->label('Fecha de contratación')
                        ->required(),
                    Forms\Components\FileUpload::make('eps')
                        ->label('EPS')
                        ->required(),

                    Forms\Components\FileUpload::make('arl')
                        ->label('ARL')
                        ->required(),

                    Forms\Components\Textarea::make('notes')
                        ->label('Observaciones')
                        ->required(),

                    Forms\Components\Toggle::make('status')
                        ->label('Activo')
                        ->required(),
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
