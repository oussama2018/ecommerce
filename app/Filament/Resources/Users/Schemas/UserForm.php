<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\DateTimePicker;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                ->required(),
                TextInput::make('email')
                ->label('Email Address')
                ->email()
                ->maxLength(255)
                ->unique(ignoreRecord:true)
                ->required(),
                DateTimePicker::make('email_verified_at')
                ->label('Email Verified At')
                ->default(now()),
                TextInput::make('password')
                ->password()
                ->dehydrated(fn ($state)=> filled($state))
                ->required(fn (Page $livewire):bool => $livewire instanceof CreateRecord),
            ]);
    }
}
