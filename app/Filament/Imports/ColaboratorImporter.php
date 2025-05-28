<?php

namespace App\Filament\Imports;

use App\Models\Colaborator;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class ColaboratorImporter extends Importer
{
    protected static ?string $model = Colaborator::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('document_type')
                ->requiredMapping()
                ->rules(['required', 'max:15']),

            ImportColumn::make('document_number')
                ->requiredMapping()
                ->rules(['required', 'max:20']),

            ImportColumn::make('first_name')
                ->requiredMapping()
                ->rules(['required', 'max:100']),

            ImportColumn::make('last_name')
                ->requiredMapping()
                ->rules(['required', 'max:100']),

            ImportColumn::make('gender')
                ->requiredMapping()
                ->rules(['required', 'max:20']),

            ImportColumn::make('birth_date')
                ->requiredMapping()
                ->rules(['required', 'date'])
                ->transform(fn(string $value) => Carbon::parse($value)),

            ImportColumn::make('personal_email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),

            ImportColumn::make('corporate_email')
                ->rules(['nullable', 'email', 'max:255']),

            ImportColumn::make('mobile')
                ->requiredMapping()
                ->rules(['required', 'max:15']),

            ImportColumn::make('phone')
                ->rules(['nullable', 'max:15']),

            ImportColumn::make('address')
                ->requiredMapping()
                ->rules(['required', 'max:150']),

            ImportColumn::make('residential_city')
                ->requiredMapping()
                ->rules(['required', 'max:100']),

            ImportColumn::make('education_level')
                ->requiredMapping()
                ->rules(['required', 'max:100']),

            ImportColumn::make('job_position')
                ->requiredMapping()
                ->rules(['required', 'max:100']),

            ImportColumn::make('hire_date')
                ->requiredMapping()
                ->rules(['required', 'date'])
                ->transform(fn(string $value) => Carbon::parse($value)),

            ImportColumn::make('status')
                ->requiredMapping()
                ->rules([
                    'required',
                    Rule::in(['activo', 'inactivo']),
                ])
                ->transform(fn(string $value) => Str::lower($value)),

            ImportColumn::make('notes')
                ->requiredMapping()
                ->rules(['required']),
        ];
    }

    public function resolveRecord(): ?Colaborator
    {
        return Colaborator::firstOrNew([
            'document_number' => $this->data['document_number'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your colaborator import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
