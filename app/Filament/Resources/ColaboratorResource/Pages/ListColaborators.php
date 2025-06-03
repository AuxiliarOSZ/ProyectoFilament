<?php

namespace App\Filament\Resources\ColaboratorResource\Pages;

use App\Filament\Resources\ColaboratorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
use App\Imports\ColaboratorsImport;


class ListColaborators extends ListRecords
{
    protected static string $resource = ColaboratorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->color('secondary')
                ->use(ColaboratorsImport::class),
            CreateAction::make(),
        ];
    }
}
