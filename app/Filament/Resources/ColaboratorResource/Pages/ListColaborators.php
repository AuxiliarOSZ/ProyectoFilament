<?php

namespace App\Filament\Resources\ColaboratorResource\Pages;

use App\Filament\Resources\ColaboratorResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
use App\Imports\ColaboratorsImport;
use Filament\Forms\Components\Actions\Action;

/**
 * Clase ListColaborators
 *
 * Página para listar los colaboradores en el recurso ColaboratorResource.
 * Permite importar colaboradores desde un archivo Excel y crear nuevos registros.
 *
 * @package App\Filament\Resources\ColaboratorResource\Pages
 */
class ListColaborators extends ListRecords
{
    /**
     * El recurso asociado a la página.
     *
     * @var string
     */
    protected static string $resource = ColaboratorResource::class;

    /**
     * Obtiene las acciones del encabezado de la página.
     *
     * @return array Acciones disponibles en el encabezado, incluyendo importación desde Excel y creación de registros.
     */
    protected function getHeaderActions(): array
    {
        return [
            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->color('secondary')
                ->use(ColaboratorsImport::class)
                ->sampleFileExcel(
                    url: asset('data/excel/colaborators-template.xlsx'),
                    sampleButtonLabel: 'Descargar plantilla',
                    customiseActionUsing: fn(Action $action) => $action->color('secondary')
                        ->icon('heroicon-m-clipboard')
                        ->requiresConfirmation(),
                ),
            CreateAction::make(),
        ];
    }
}