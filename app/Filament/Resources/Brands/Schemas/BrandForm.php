<?php

namespace App\Filament\Resources\Brands\Schemas;

use App\Models\Brand;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Utilities\Set;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                Grid::make()
                ->schema([
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
                    ->unique(Brand::class,'slug',ignoreRecord:true),
                    FileUpload::make('image')
                    ->image()
                    ->directory('brands')
                    ->disk('cloudinary')
                    ->fetchFileInformation(false),
                    Toggle::make('is_active')
                    ->default(true)
                    ->required()
                ]),
                ])
                ->columnSpanFull()
            ]);
    }
}
