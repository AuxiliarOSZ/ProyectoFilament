<?php

namespace App\Filament\Resources\ColaboratorResource\Pages;

use App\Filament\Resources\ColaboratorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListColaborators extends ListRecords
{
    protected static string $resource = ColaboratorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
