<?php

namespace App\Filament\Resources\ColaboratorResource\Pages;

use App\Filament\Resources\ColaboratorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions\CreateAction;



class ListColaborators extends ListRecords
{
    protected static string $resource = ColaboratorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExcelImportAction::make()
                ->color('secondary'),
            CreateAction::make(),
        ];
    }
}
