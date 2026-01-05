<?php

namespace App\Filament\Admin\Resources\Licenses\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LicenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('purchase_id')
                    ->required()
                    ->numeric(),
                TextInput::make('product_id')
                    ->required()
                    ->numeric(),
                TextInput::make('owner_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('sold_by_user_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('license_key')
                    ->required(),
                TextInput::make('domain')
                    ->default(null),
                Select::make('status')
                    ->options(['inactive' => 'Inactive', 'active' => 'Active', 'expired' => 'Expired', 'revoked' => 'Revoked'])
                    ->default('inactive')
                    ->required(),
                Select::make('type')
                    ->options(['paid' => 'Paid', 'trial' => 'Trial', 'unpaid' => 'Unpaid'])
                    ->required(),
                DateTimePicker::make('activated_at'),
                DateTimePicker::make('expires_at'),
            ]);
    }
}
