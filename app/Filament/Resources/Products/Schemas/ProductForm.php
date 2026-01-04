<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('name')
                ->label('Product Name')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),

            Forms\Components\Select::make('type')
                ->label('Product Type')
                ->options([
                    'plugin' => 'Plugin',
                    'theme' => 'Theme',
                    'flutter_app' => 'Flutter App',
                ])
                ->required(),

            Forms\Components\Toggle::make('supports_trial')
                ->label('Supports Trial')
                ->default(false),

            Forms\Components\TextInput::make('trial_days')
                ->label('Trial Days')
                ->numeric()
                ->minValue(1)
                ->maxValue(365)
                ->visible(fn ($get) => $get('supports_trial')),
        ]);
    }
}
