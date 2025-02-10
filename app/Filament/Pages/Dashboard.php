<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
// use Filament\Pages\Page;

class Dashboard extends \Filament\Pages\Dashboard
{
    // protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // protected static string $view = 'filament.pages.dashboard';

    use HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        // return $form;
        return $form->schema([
            Section::make('')->schema([
                // TextInput::make('name'),
                DatePicker::make('startDate'),
                DatePicker::make('endDate'),
                // Toggle::make('active'),
            ])->columns(2)
        ]);
    }
}
