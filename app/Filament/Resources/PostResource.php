<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use App\Models\Navmenu;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\PostResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Filament\Resources\PostResource\RelationManagers\PostcategoryRelationManager;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square-stack';

    // protected static ?string $modelLabel = 'Post Categories';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Post')
                    ->description('here is fill for post')
                    ->icon('heroicon-o-arrow-down-on-square-stack')
                    ->schema(
                        [
                            TextInput::make('name')
                                ->required(),

                            TextInput::make('title')
                                ->required(),

                            ColorPicker::make('color')
                                ->required(),

                            TextInput::make('tags')
                                ->required(),


                            Select::make('navmenu_id')
                                ->label('Menu')
                                ->options(Navmenu::pluck('name', 'id')->all()),

                            Select::make('category_id')
                                ->label('Category')
                                // ->relationship(name:'category', titleAttribute:'name')->searchable()->required(),
                                ->options(Category::pluck('name')->all()),

                            Checkbox::make('published')
                                ->required(),

                            Radio::make('status')
                                ->label('Like this post?')
                                ->options([
                                    'Active' => 'Active',
                                    'Inactive' => 'Inactive',
                                ])
                                ->inline(),
                        ]
                    )->columnSpan(2)->columns(3),
                Group::make()->schema(
                    [
                        Section::make('Meta Posts Details')
                            ->collapsible()
                            ->description('This is optional information for post creation.')
                            ->icon('heroicon-o-folder-plus')
                            ->schema([
                                FileUpload::make('image')
                                    ->required(),

                                FileUpload::make('thumbnail')
                                    ->disk('public')
                                    ->directory('thumbnail')
                                    ->visibility('public'),

                            ])->columnSpan(1),
                    ]
                ),
                Group::make()->schema(
                    [
                        Section::make('Write Descriptions here')
                            ->collapsible()
                            ->icon('heroicon-o-document-currency-bangladeshi')

                            ->schema([
                                MarkdownEditor::make('content')
                                    ->required(),
                                // That's use for specific column width full , middle sizes for pics ,
                                //1. ->columnSpan('full'),
                                //2. ->columnSpanFull()
                            ])
                    ]
                )->columnSpan(2),
                Group::make()->schema(
                    [
                        Section::make('Posts Category Details')
                            ->collapsible()
                            ->description('For Check Many to Many Relationship.')
                            ->icon('heroicon-o-clipboard-document-check')
                            ->schema([
                               CheckboxList::make('post_category')
                               ->label('Co Post Category:')
                               ->searchable()
                            //    ->multiple()
                               ->relationship('post_category' , 'name')
                            ])
                    ]
                ),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\ColorColumn::make('color'),
                Tables\Columns\TextColumn::make('tags')->searchable(),
                Tables\Columns\CheckboxColumn::make('published'),
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->disk('public')
                    ->label('Thumbnail'),
                Tables\Columns\TextColumn::make('navmenu.id')->sortable(),
                Tables\Columns\TextColumn::make('category.name')->sortable(),
                Tables\Columns\TextColumn::make('content')->limit(20),
                Tables\Columns\TextColumn::make('status')->sortable()->searchable()->toggleable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('created_at')->since(),
                Tables\Columns\TextColumn::make('post_category.name')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PostcategoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
