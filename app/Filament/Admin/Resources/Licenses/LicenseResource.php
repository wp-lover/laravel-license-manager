<?php

namespace App\Filament\Admin\Resources\Licenses;

use App\Filament\Admin\Resources\Licenses\Pages\CreateLicense;
use App\Filament\Admin\Resources\Licenses\Pages\EditLicense;
use App\Filament\Admin\Resources\Licenses\Pages\ListLicenses;
use App\Filament\Admin\Resources\Licenses\Pages\ViewLicense;
use App\Filament\Admin\Resources\Licenses\Schemas\LicenseForm;
use App\Filament\Admin\Resources\Licenses\Schemas\LicenseInfolist;
use App\Filament\Admin\Resources\Licenses\Tables\LicensesTable;
use App\Models\License;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LicenseResource extends Resource
{
    protected static ?string $model = License::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return LicenseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LicenseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LicensesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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
