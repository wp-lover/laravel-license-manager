<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Products\Schemas\ProductForm;
use App\Filament\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use BackedEnum; // Needed for type hint only

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

   /**
     * Filament sidebar icon.
     * Must be string|BackedEnum|null
     */
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;


    // Attribute to display as record title
    protected static ?string $recordTitleAttribute = 'name';

    /**
     * Form schema for creating/editing a product
     */
    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    /**
     * Table schema for listing products
     */
    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
    }

    /**
     * Relations (optional)
     */
    public static function getRelations(): array
    {
        return [
            // Add RelationManagers here if needed
        ];
    }

    /**
     * Resource pages (list, create, edit)
     */
    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
