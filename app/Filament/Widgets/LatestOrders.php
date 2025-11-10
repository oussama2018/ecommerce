<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Widgets\TableWidget;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Orders\OrderResource;

class LatestOrders extends TableWidget
{
    protected int| string| array $columnSpan='full';
    protected static ?int $sort=2;
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Order::query())
            ->columns([
                TextColumn::make('id')
                    ->label('Order ID')
                    ->searchable(),
                TextColumn::make('user.name')
                ->searchable(),
                TextColumn::make('grand_total'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state)=>match($state){
                        'new'=>'info',
                        'processing'=>'warning',
                        'shipped'=>'success',
                        'delivered'=>'success',
                        'cancelled'=>'danger'
                    })
                    ->icon(fn(string $state)=>match($state){
                        'new'=>'heroicon-m-sparkles',
                        'processing'=>'heroicon-m-arrow-path',
                        'shipped'=>'heroicon-m-truck',
                        'delivered'=>'heroicon-m-check-badge',
                        'cancelled'=>'heroicon-m-x-circle'
                    }),
                TextColumn::make('payment_method')
                    ->searchable(),
                TextColumn::make('payment_status')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Date'),
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
            ->headerActions([
                //
            ])
            ->recordActions([
                Action::make('View Order')
                    ->url(fn(Order $record):string=>OrderResource::getUrl('view',['record'=>$record]))
                    ->color('info')
                    ->icon('heroicon-o-eye'),
                /* EditAction::make(), */
              /*   DissociateAction::make(), */
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
