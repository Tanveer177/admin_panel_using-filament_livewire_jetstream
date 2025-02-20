<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\City;
use App\Models\Country;
use App\Models\Department;
use App\Models\State;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Htmlable;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group'; // The icon used in the navigation
    protected static ?string $navigationLabel = 'Employee'; // The label used in the navigation
    protected static ?string $modelLabel = 'Employee'; // The label used in the resource index
    //Group name under which the resource will be displayed
    protected static ?string $navigationGroup = 'System Management';
    //position of the resource
    protected static ?int $navigationSort = 5;
    //Global Search base on this attribute
    //1 Methods
    protected static ?string $recordTitleAttribute = 'first_name';
    //2 Methods
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->last_name;
    }
    // 3 Mehtods
    public static function getGloballySearchableAttributes(): array
    {
        return ['first_name', 'last_name', 'middle_name', 'country.name'];
    }
    //4 methods
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Country' => $record->country->name
        ];
    }
    //5 Methods 
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['country']);
    }
    // Resources Count With badged
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('country_id')
                    ->relationship(name: 'country',  titleAttribute: 'name')
                    // ->options(Country::pluck('name', 'id')->all())
                    ->live()
                    ->searchable()->preload()
                    ->afterStateUpdated(function (Set $set) {
                        $set('state_id', null);
                        $set('city_id', null);
                    }) // This will reset the state and city fields when the country field is changed
                    ->required(),
                Forms\Components\Select::make('state_id')
                    // ->relationship(name: 'state', titleAttribute: 'name') // ->options(State::pluck('name', 'id')->all())   // This will be used when we have a small number of records in the table
                    ->searchable()->preload()
                    ->options(
                        fn(Get $get): Collection => State::query()
                            ->where('country_id', $get('country_id'))->pluck('name', 'id')
                    )
                    ->live()
                    ->required(),
                Forms\Components\Select::make('city_id')
                    // ->relationship(name: 'city', titleAttribute: 'name') // ->options(City::pluck('name', 'id')->all())
                    ->searchable()->preload()
                    ->options(
                        fn(Get $get): Collection => City::query()
                            ->where('state_id', $get('state_id'))->pluck('name', 'id')
                    )
                    ->live()
                    ->required(),
                Forms\Components\Select::make('department_id')
                    ->relationship(name: 'department', titleAttribute: 'name')
                    // ->options(Department::pluck('name', 'id')->all())
                    ->searchable()->preload()
                    ->required(),
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('middle_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('zip_code')
                    ->required()
                    ->maxLength(10),
                Forms\Components\DatePicker::make('date_of_birth')
                    ->required(),
                Forms\Components\DatePicker::make('date_hired')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('country.name')
                    ->label('Country')
                    ->sortable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('middle_name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('address')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('zip_code')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_hired')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('state.name')->label('State')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('city.name')->label('City')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')->label('Department')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('Department')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(), // This will show a confirmation dialog before deleting the record 
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Employees Information')->schema([
                    TextEntry::make('country.name')->label('Country Name'),
                    TextEntry::make('state.name')->label('State Name'),
                    TextEntry::make('city.name')->label('City Name'),
                    TextEntry::make('department.name')->label('Department Name'),
                ])->columns(2)
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            // 'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
