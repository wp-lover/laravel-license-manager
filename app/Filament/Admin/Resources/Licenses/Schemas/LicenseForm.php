<?php

namespace App\Filament\Admin\Resources\Licenses\Schemas;

use App\Models\Product;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Schema;

class LicenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('product_id')
                ->label('Product')
                ->options(Product::pluck('name', 'id')->toArray())
                ->searchable()
                ->required(),

            TextInput::make('quantity')
                ->label('Number of Licenses to Generate')
                ->numeric()
                ->minValue(1)
                ->default(1)
                ->required()
                ->helperText('How many identical licenses to create'),

            TextInput::make('max_domains')
                ->label('Max Domains per License')
                ->numeric()
                ->minValue(1)
                ->default(1)
                ->required()
                ->helperText('e.g. 1 = single domain, 3 = multi-site'),

            Select::make('owner_id')
                ->label('Customer (Owner)')
                ->options(User::pluck('name', 'id')->toArray())
                ->searchable()
                ->nullable(),

            Select::make('sold_by_user_id')
                ->label('Sold/Issued By')
                ->options(User::pluck('name', 'id')->toArray())
                ->searchable()
                ->nullable(),

            Select::make('status')
                ->options([
                    'inactive' => 'Inactive',
                    'active'   => 'Active',
                    'expired'  => 'Expired',
                    'revoked'  => 'Revoked',
                ])
                ->default('inactive'),

            Select::make('type')
                ->options([
                    'paid'   => 'Paid',
                    'trial'  => 'Trial',
                    'unpaid' => 'Unpaid',
                ])
                ->default('unpaid'),

            TextInput::make('total_amount')
                ->label('Total Amount (BDT)')
                ->numeric()
                ->minValue(0)
                ->prefix('৳')
                ->required()
                ->default(fn($get) => ($get('product_id') ? Product::find($get('product_id'))?->price * ($get('quantity') ?? 1) : 0))
                ->reactive()
                ->afterStateUpdated(function ($state, $get, $set) {
                    // Optional: warn if free but not checked
                })
                ->helperText('Total for all licenses (can override for discount)'),

            Checkbox::make('is_free')
                ->label('This is a free license')
                ->reactive()
                ->afterStateUpdated(function ($state, $get, $set) {
                    if ($state) {
                        $set('total_amount', 0);
                        $set('type', 'trial'); // or 'free'
                    }
                }),

            Checkbox::make('later_pay')
                ->label('Later pay (pending payment)')
                ->reactive()
                ->afterStateUpdated(function ($state, $get, $set) {
                    if ($state) {
                        $set('type', 'unpaid');
                    }
                }),

            DateTimePicker::make('activated_at')
                ->nullable(),

            DateTimePicker::make('expires_at')
                ->nullable(),
        ]);
    }
}
