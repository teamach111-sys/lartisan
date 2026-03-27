<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Produit;
use App\Models\SignalementProduit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $newUsersToday = User::whereDate('created_at', '=', Carbon::today(), 'and')->count('*');
        $totalProduits = Produit::count('*');
        $pendingReports = SignalementProduit::where('est_traite', '=', false, 'and')->count('*');

        return [
            Stat::make('Nouveaux Utilisateurs (Aujourd\'hui)', $newUsersToday)
                ->description('Inscriptions depuis minuit')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
            Stat::make('Total Produits', $totalProduits)
                ->description('Articles en ligne')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),
            Stat::make('Signalements en attente', $pendingReports)
                ->description('À traiter d\'urgence')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($pendingReports > 0 ? 'danger' : 'success'),
        ];
    }
}
