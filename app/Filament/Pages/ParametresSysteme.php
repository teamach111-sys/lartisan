<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
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
    protected string $view = 'filament.pages.parametres-systeme';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = SiteSetting::first();
        $this->form->fill([
            'favicon' => $setting?->favicon,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
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

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Enregistrer')
                ->submit('save'),
        ];
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
