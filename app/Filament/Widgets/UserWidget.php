<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Colaborator;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Usuarios', User::count())
                ->description('Total de usuarios registrados')
                ->chart([10, 20, 10, 30, 10, 20, 10])
                ->color('success'),
            Stat::make('Colaboradores', Colaborator::count())
                ->description('Total de colaboradores registrados')
                ->chart([10, 20, 10, 30, 10, 20, 10])
                ->color('success'),
        ];
    }
}
