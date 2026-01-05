<?php

namespace App\Filament\Admin\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Select::make('type')
                    ->options(['wp_plugin' => 'Wp plugin', 'wp_theme' => 'Wp theme', 'flutter_app' => 'Flutter app'])
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('current_version')
                    ->required()
                    ->default('1.0.0'),
                TextInput::make('update_url')
                    ->url()
                    ->default(null),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('$'),
                Toggle::make('supports_trial')
                    ->required(),
                TextInput::make('trial_days')
                    ->numeric()
                    ->default(null),
                Toggle::make('is_active')
                    ->required(),
                Textarea::make('metadata')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
