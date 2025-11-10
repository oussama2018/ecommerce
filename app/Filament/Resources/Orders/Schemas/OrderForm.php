<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Product;
use BcMath\Number;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Laravel\Pail\Options;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()->schema([
                    Section::make('Order Information')->schema([
                        Select::make('user_id')
                        ->label('Customer')
                        ->searchable()
                        ->required()
                        ->preload()
                        ->relationship('user','name'),
                        Select::make('payment_method')
                        ->label('Payment method')
                        ->options([
                            'stripe'=>'Stripe',
                            'cod'=>'Cash On Delivery'
                        ])
                        ->required(),
                        Select::make('payment_status')
                        ->label('Payment Status')
                        ->options([
                            'pending'=>'Pending',
                            'paid'=>'Paid',
                            'failed'=>'Failed'

                        ])->default('pending')
                        ->required(),
                        ToggleButtons::make('status')
                        ->inline()
                        ->default('new')
                        ->required()
                        ->options([
                            'new'=>'New',
                            'processing'=>'Processing',
                            'shipped'=>'Shipped',
                            'delivered'=>'Delivered',
                            'cancelled'=>'Cancelled'
                        ])
                        ->icons([
                        'new' => 'heroicon-o-sparkles',
                        'processing' => 'heroicon-o-clock',
                        'shipped' => 'heroicon-o-truck',
                        'delivered' => 'heroicon-o-check-circle',
                        'cancelled' => 'heroicon-o-x-circle',
                        ])
                        ->colors([
                            'new'=>'gray',
                            'processing'=>'primary',
                            'shipped'=>'info',
                            'delivered'=>'success',
                            'cancelled'=>'danger'
                        ]),
                        Select::make('currency')
                        ->options([
                            '$' => 'USD',
                            '€' => 'EUR',
                        ])
                        ->default('$')
                        ->required(),
                        Select::make('shipping_method')
                        ->options([
                            'fedex'=>'FedEx',
                            'ups'=>'UPS',
                            'dhl'=>'DHL',
                            'usps'=>'USPS'
                        ])
                        ->required(),
                        Textarea::make('notes')
                        ->columnSpanFull()
                    ])->columns(2),
                    Section::make('Order Item')->schema([
                        Repeater::make('items')
                        ->relationship()
                        ->schema([
                            Select::make('product_id')
                            ->label('Product')
                            ->relationship('product','name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->distinct()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->columnSpan(4)
                            ->reactive()
                            ->afterStateUpdated(fn($state, Set $set)=>$set('unit_amount',Product::find($state)?->price ?? 0))
                            ->afterStateUpdated(fn($state, Set $set)=>$set('total_amount',Product::find($state)?->price ?? 0)),
                            TextInput::make('quantity')
                            ->numeric()
                            ->integer()
                            ->minValue(1)
                            ->default(1)
                            ->required()
                            ->columnSpan(2)
                            ->reactive()
                            ->afterStateUpdated(fn($state, Set $set, Get $get)=>$set('total_amount', $state * $get('unit_amount'))),
                            TextInput::make('unit_amount')
                            ->numeric()
                            ->disabled()
                            ->required()
                            ->dehydrated()
                            ->columnSpan(3),
                            TextInput::make('total_amount')
                            ->numeric()
                            ->required()
                            ->dehydrated()
                            ->columnSpan(3),
                        ])->columns(12),

                        Placeholder::make('grand_total_placehoder')
                        ->label('Grand Total')
                        ->content(function (Get $get, Set $set) {
                        $total = 0;
                        if (!$repeaters =$get('items')) {
                            return $total;
                        }
                        foreach($repeaters as $key=>$repeater){
                            $total+= $get("items.{$key}.total_amount");
                        }
                        $set('grand_total',$total);
                        return number_format($total, 2) . ' ' . $get('currency');

                         }),
                         Hidden::make('grand_total')
                         ->default(0)

                        ->columnSpanFull(),

                        ]),

                ])->columnSpanFull()
            ]);
    }
}
