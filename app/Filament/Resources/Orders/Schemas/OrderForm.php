<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()->schema([
                    Section::make('Order Information')->schema([
                        Select::make('user-id')
                        ->label('Customer')
                        ->searchable()
                        ->required()
                        ->preload()
                        ->relationship('user','name'),
                        Select::make('payment_method')
                        ->label('Payment method')
                        ->options([
                            'stripe'=>'Stripe',
                            'cod'=>'Cash On Drlivery'
                        ])
                        ->required(),
                        Select::make('payment_status')
                        ->label('Payment Status')
                        ->options([
                            'pending'=>'Pending'

                        ])
                    ])
                ])
            ]);
    }
}
