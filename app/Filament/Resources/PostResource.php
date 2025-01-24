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
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\{Group, Radio, Select, Section, Checkbox, Textarea, TextInput, FileUpload, ColorPicker, CheckboxList, MarkdownEditor};
use Filament\Tables\Columns\{ImageColumn, TextColumn, ColorColumn, CheckboxColumn};
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers\PostcategoryRelationManager;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Post')
                    ->description('Fill in the post details')
                    ->icon('heroicon-o-arrow-down-on-square-stack')
                    ->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('title')->required(),
                        ColorPicker::make('color')->required(),
                        TextInput::make('tags')->required(),
                        Select::make('navmenu_id')->label('Menu')->options(Navmenu::pluck('name', 'id')->all()),
                        Select::make('category_id')->label('Category')->options(Category::pluck('name', 'id')->all()),
                        Checkbox::make('published')->nullable(),
                        Radio::make('status')
                            ->label('Like this post?')
                            ->options(['Active' => 'Active', 'Inactive' => 'Inactive'])
                            ->inline(),
                    ])->columns(2),
                Group::make()->schema([
                    Section::make('Meta Posts Details')
                        ->collapsible()
                        ->description('Optional post information')
                        ->icon('heroicon-o-folder-plus')
                        ->schema([
                            FileUpload::make('image')->required(),
                            FileUpload::make('thumbnail')->disk('public')->directory('thumbnail')->visibility('public'),
                        ]),
                ]),
                Group::make()->schema([
                    Section::make('Write Descriptions here')
                        ->collapsible()
                        ->icon('heroicon-o-document-currency-bangladeshi')
                        ->schema([
                            MarkdownEditor::make('content')->required(),
                        ]),
                ])->columnSpan(2),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('title')->searchable(),
                ColorColumn::make('color'),
                TextColumn::make('tags')->searchable(),
                CheckboxColumn::make('published'),
                ImageColumn::make('thumbnail')->disk('public')->label('Thumbnail'),
                TextColumn::make('navmenu.id')->sortable(),
                TextColumn::make('category.name')->sortable(),
                TextColumn::make('content')->limit(20),
                TextColumn::make('status')->sortable()->searchable()->toggleable(),
                ImageColumn::make('image'),
                TextColumn::make('created_at')->since(),
            ])
            ->filters([
                Filter::make('Published Posts')->query(fn(Builder $query): Builder => $query->where('published', true)),
                SelectFilter::make('category_id')->options(Category::pluck('name', 'id')->all())->multiple(),
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
