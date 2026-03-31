<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix Filament/Livewire infinite loading on S3/R2 by forcing local temporary uploads
        config(['livewire.temporary_file_upload.disk' => 'local']);

        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            // Share categories and cities globally
            $view->with('categories', \App\Models\Categorie::all());
            $view->with('villes', \App\Models\Ville::orderBy('nom')->get());

            if (auth()->check()) {
                $unreadCount = \App\Models\Message::whereHas('conversation', function($query) {
                    $query->where(function($q) {
                        $q->where('acheteur_id', auth()->id())
                          ->orWhereHas('produit', function($sq) {
                              $sq->where('vendeur_id', auth()->id());
                          });
                    });
                })
                ->where('expediteur_id', '!=', auth()->id())
                ->where('est_lu', false)
                ->count();
                
                $view->with('unreadCount', $unreadCount);
            } else {
                $view->with('unreadCount', 0);
            }
        });
    }
}
