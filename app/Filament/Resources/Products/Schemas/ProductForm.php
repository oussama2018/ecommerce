<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use App\Models\Product;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Product Information')->schema([
                        TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur:true)
                        ->afterStateUpdated(fn (string $operation, $state, Set $set)=>$operation==='create'? $set('slug', Str::slug($state)):null),
                        TextInput::make('slug')
                         ->required()
                        ->disabled()
                        ->dehydrated()
                        ->maxLength(255)
                        ->unique(Product::class,'slug',ignoreRecord:true),
                        MarkdownEditor::make('description')
                        ->columnSpanFull()
                        ->fileAttachmentsDisk('cloudinary')
                        ->fileAttachmentsDirectory('products')
                    ])->columns(2),
                    Section::make('Images')->schema([
                        FileUpload::make('image')
                        ->multiple()
                        ->directory('products')
                        ->maxFiles(5)
                        ->reorderable()
                        ->disk('cloudinary')
                        ->fetchFileInformation(false),
                    ])
                    ])->columnSpan(2),
                Group::make([
                    Section::make('Price')->schema([
                        TextInput::make('price')
                        ->required()
                        ->numeric()
                        ->prefix('$')
                    ]),
                    Section::make('Association')->schema([
                        Select::make('category_id')
                        ->label('Category')
                        ->searchable()
                        ->required()
                        ->preload()
                        ->relationship('category','name'),
                        Select::make('brand_id')
                        ->label('Brand')
                        ->searchable()
                        ->required()
                        ->preload()
                        ->relationship('brand','name')
                    ]),
                    Section::make('Status')->schema([
                    Toggle::make('in_stock')
                    ->required()
                    ->default(true),
                    Toggle::make('is_active')
                    ->required()
                    ->default(true),
                    Toggle::make('is_featured')
                    ->required()
                    ->default(false),
                    Toggle::make('on_sale')
                    ->required()
                    ->default(false)
                    ]),
                ])


            ])
            ->columns(3);
    }
}
