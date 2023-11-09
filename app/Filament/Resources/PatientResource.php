<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Resources\PatientResource\RelationManagers;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;
    protected static ?string $pluralModelLabel = "Pacientes";
    protected static ?string $modelLabel = "Paciente";

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nome paciente')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('type')
                        ->label('Especie')
                        ->options([
                            'cat'       => 'Gato',
                            'dog'       => 'Cachorro',
                            'rabbit'    => 'Coelho',
                            'snake'     => 'Cobra',
                            'bird'      => 'Passaro',
                        ])
                        ->required(),
                    Forms\Components\DatePicker::make('date_of_birth')
                        ->label('Data de nascimento')
                        ->required()
                        ->maxDate(now()),
                ])->columns(3),
                Forms\Components\Select::make('owner_id')
                    ->label('Tutor')
                    ->relationship('owner', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Phone number')
                            ->tel()
                            ->required(),
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Especie'),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label('Data nascimento')
                    ->sortable(),
                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Tutor')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'cat' => 'Gato',
                        'dog' => 'Cachorro',
                        'rabbit' => 'Coelho',
                        'snake' => 'Cobra',
                        'bird' => 'Passaro',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            RelationManagers\TreatmentsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }    
}
