<?php

namespace App\Filament\Admin\Resources\Licenses\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LicenseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('purchase_id')
                    ->numeric(),
                TextEntry::make('product_id')
                    ->numeric(),
                TextEntry::make('owner_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('sold_by_user_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('license_key'),
                TextEntry::make('domain')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('type')
                    ->badge(),
                TextEntry::make('activated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('expires_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
