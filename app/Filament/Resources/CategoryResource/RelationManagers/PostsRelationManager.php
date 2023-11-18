<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dados básicos da postagem')
                    ->description('Criação de postagem')
                    ->collapsible()
                    ->schema([

                        Forms\Components\Group::make()->schema([
                            TextInput::make('title')
                                ->required()
                                ->rules(['alpha_num'])
                                ->maxLength(255),
                            ColorPicker::make('color')
                                ->required(),
                        ]),

                        Select::make('category_id')
                            ->label('Categoria')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                ])->columnSpan(1)->columns(2),




                Section::make('Dados de publicação')
                    ->schema([
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
                        FileUpload::make('thumbnail')
                            ->disk('public')
                            ->directory('thumbnails')->columnSpanFull(),
                    ])->columnSpan(1)->columns(2),

                RichEditor::make('content')
                    ->columnSpanFull()
                    ->maxLength(65535)
                    ->columnSpanFull(),

            ])->columns([
        'default'   => 1,
        'md'        => 2,
        'lg'        => 2,
        'xl'        => 2,
    ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                IconColumn::make('published')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
