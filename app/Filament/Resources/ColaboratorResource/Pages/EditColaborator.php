<?php

namespace App\Filament\Resources\ColaboratorResource\Pages;

use App\Filament\Resources\ColaboratorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditColaborator extends EditRecord
{
    protected static string $resource = ColaboratorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
