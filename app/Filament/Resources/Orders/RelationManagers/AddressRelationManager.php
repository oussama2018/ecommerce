<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AddressRelationManager extends RelationManager
{
    protected static string $relationship = 'address';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                ->required()
                ->maxLength(255),
                TextInput::make('last_name')
                ->required()
                ->maxLength(255),
                TextInput::make('phone')
                ->required()
                ->tel()
                ->maxLength(20),
                TextInput::make('city')
                ->required()
                ->maxLength(255),
                TextInput::make('state')
                ->required()
                ->maxLength(255),
                TextInput::make('zip_code')
                ->label('Zip Code')
                ->required()
                ->numeric()
                ->maxLength(20),
                TextInput::make('street_address')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('street_address')
            ->columns([
                TextColumn::make('full_name')
                ->label('Full Name')
                ->getStateUsing(fn($record) => $record->first_name . ' ' . $record->last_name)
                ->searchable(query: fn ($query, $search) =>
                    $query->where('first_name', 'like', "%{$search}%")
                          ->orWhere('last_name', 'like', "%{$search}%")
                )
                ->sortable(query: fn ($query, $direction) =>
                    $query->orderBy('first_name', $direction)
                          ->orderBy('last_name', $direction)
                ),
                TextColumn::make('phone')
                ->searchable()
                ->sortable(),
                TextColumn::make('city')
                ->searchable()
                ->sortable(),
                TextColumn::make('state')
                ->searchable()
                ->sortable(),
                TextColumn::make('zip_code')
                ->searchable()
                ->sortable(),
                TextColumn::make('street_address')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ])

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
