<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Category;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $pluralModelLabel = "Blog";
    protected static ?string $modelLabel = "noticia";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Select::make('category_id')
                    ->label('Categoria')
                    ->required()
                    ->options(Category::all()->pluck('name', 'id')),
                FileUpload::make('thumbnail')
                    ->disk('public')
                    ->directory('thumbnails'),
                ColorPicker::make('color')
                    ->required(),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                RichEditor::make('content')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                TagsInput::make('tags')
                    ->required()
                    ->suggestions([
                        'tailwindcss',
                        'alpinejs',
                        'laravel',
                        'livewire',
                    ]),
                Toggle::make('published')
                    ->required(),
            ])->columns(10);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Categoria')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable(),
                ColorColumn::make('color')
                    ->searchable(),
                TextColumn::make('tags')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                IconColumn::make('published')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            //
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
