<?php

namespace App\Filament\Admin\Resources\Licenses;

use App\Filament\Admin\Resources\Licenses\Pages\ListLicenses;
use App\Filament\Admin\Resources\Licenses\Pages\CreateLicense;
use App\Filament\Admin\Resources\Licenses\Pages\ViewLicense;
use App\Filament\Admin\Resources\Licenses\Pages\EditLicense;
use App\Filament\Admin\Resources\Licenses\Schemas\LicenseForm;
use App\Models\License;
use App\Models\Product;
use App\Models\Purchase;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use BackedEnum;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
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

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $product = Product::find($data['product_id']);
        $quantity = max(1, $data['quantity'] ?? 1);

        // Determine status/type based on checkboxes
        $type = $data['type'] ?? 'paid';
        if ($data['is_free'] ?? false) {
            $data['total_amount'] = 0;
            $type = 'trial'; // or 'free' if you add that option
        }
        if ($data['later_pay'] ?? false) {
            $type = 'unpaid';
        }

        // Step 1: Create the Purchase record (always)
        $purchase = Purchase::create([
            'buyer_id'      => $data['owner_id'] ?? auth()->user()->id,
            'product_id'    => $data['product_id'],
            'quantity'      => $quantity,
            'total_amount'  => $data['total_amount'] ?? ($product->price * $quantity),
            'currency'      => 'BDT',
            'status'        => ($data['later_pay'] ?? false) ? 'pending' : 'completed',
            'purchased_at'  => now(),
        ]);

        $firstLicense = null;

        for ($i = 0; $i < $quantity; $i++) {
            $key = strtoupper($product->slug . '-' . Str::random(4) . '-' . Str::random(4));

            $license = License::create([
                'purchase_id'       => $purchase->id,          // ← Now always set
                'product_id'        => $data['product_id'],
                'owner_id'          => $data['owner_id'] ?? null,
                'sold_by_user_id'   => auth()->user()->id,
                'license_key'       => $key,
                'status'            => in_array($type, ['paid', 'trial']) ? 'active' : 'inactive',
                'type'              => $type,
                'activated_at'      => $data['activated_at'] ?? null,
                'expires_at'        => $data['expires_at'] ?? null,
            ]);

            if (!$firstLicense) {
                $firstLicense = $license;
            }
        }

        Notification::make()
            ->title("{$quantity} license(s) created successfully!")
            ->success()
            ->send();

        return $firstLicense;
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
