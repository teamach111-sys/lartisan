<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use BackedEnum;
use UnitEnum;

class ParametresSysteme extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static string|UnitEnum|null $navigationGroup = 'Système';
    protected static ?string $title = 'Paramètres du site';
    
    protected string $view = 'filament.pages.parametres-systeme';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = null;
        try {
            $setting = SiteSetting::first();
        } catch (\Exception $e) {
            // Table might missing
        }
        
        $this->form->fill([
            'favicon' => $setting?->favicon,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                FileUpload::make('favicon')
                    ->label('Favicon du site (PNG, ICO)')
                    ->image()
                    ->acceptedFileTypes(['image/png', 'image/x-icon', 'image/vnd.microsoft.icon'])
                    ->disk(config('filesystems.default', 'public'))
                    ->directory('settings')
                    ->visibility('public')
                    ->maxSize(2048), // 2MB max
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $setting = SiteSetting::firstOrNew(['id' => 1]);
        $setting->favicon = $data['favicon'] ?? null;
        $setting->save();

        Notification::make()
            ->title('Paramètres enregistrés avec succès')
            ->success()
            ->send();
    }
}
