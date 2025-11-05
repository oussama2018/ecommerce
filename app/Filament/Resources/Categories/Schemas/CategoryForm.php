<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Utilities\Set;

class CategoryForm
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
                        ->maxLength(255)
                        ->dehydrated()
                        ->unique(Category::class,'slug',ignoreRecord:true),
                        FileUpload::make('image')
                        ->image()
                        ->directory('categories')
                        ->disk('cloudinary')
                        ->fetchFileInformation(false),
                         Toggle::make('is_active')
                        ->required()
                        ->default(true)

                    ])
                ])
                ->columnSpanFull()
            ]);
    }
}
