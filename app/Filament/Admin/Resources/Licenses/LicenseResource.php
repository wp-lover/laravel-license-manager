<?php

namespace App\Filament\Admin\Resources\Licenses;

use App\Filament\Admin\Resources\Licenses\Pages\ListLicenses;
use App\Filament\Admin\Resources\Licenses\Pages\CreateLicense;
use App\Filament\Admin\Resources\Licenses\Pages\ViewLicense;
use App\Filament\Admin\Resources\Licenses\Pages\EditLicense;
use App\Filament\Admin\Resources\Licenses\Schemas\LicenseForm;
use App\Models\License;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use BackedEnum;
use UnitEnum;

class LicenseResource extends Resource
{
    protected static ?string $model = License::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-key';

    protected static string|UnitEnum|null $navigationGroup = 'Licenses';

    protected static ?string $recordTitleAttribute = 'license_key';

    public static function form(Schema $schema): Schema
    {
        return LicenseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('license_key')->searchable()->sortable(),

                Tables\Columns\TextColumn::make('product.name')->label('Product'),

                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Owner')

                    ->placeholder('Unassigned'),

                Tables\Columns\TextColumn::make('status')->badge(),

                Tables\Columns\TextColumn::make('type')->badge(),

                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

  
    public static function getPages(): array
    {
        return [
            'index' => ListLicenses::route('/'),
            'create' => CreateLicense::route('/create'),
            'view' => ViewLicense::route('/{record}'),
            'edit' => EditLicense::route('/{record}/edit'),
        ];
    }
}
