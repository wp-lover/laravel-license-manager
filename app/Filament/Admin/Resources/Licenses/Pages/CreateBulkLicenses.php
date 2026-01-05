<?php

namespace App\Filament\Admin\Resources\LicenseResource\Pages;

use App\Filament\Admin\Resources\Licenses\LicenseResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;
use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use App\Models\License;
use App\Models\Purchase;
use Illuminate\Support\Str;

class CreateBulkLicenses extends CreateRecord
{
    protected static string $resource = LicenseResource::class;

    protected static ?string $title = 'Create Bulk Licenses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Bulk License Creation')
                    ->description('Generate multiple licenses for a customer with optional discount.')
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->label('Product')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $product = \App\Models\Product::find($state);
                                $set('unit_price', $product?->price ?? 0);
                                $set('quantity', 1);
                                $set('total_amount', $product?->price ?? 0);
                            }),

                        Forms\Components\TextInput::make('unit_price')
                            ->label('Price per License (BDT)')
                            ->numeric()
                            ->readOnly()
                            ->prefix('৳')
                            ->reactive(),

                        Forms\Components\TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $unit = $get('unit_price') ?? 0;
                                $set('total_amount', $unit * ($state ?? 1));
                            }),

                        Forms\Components\TextInput::make('total_amount')
                            ->label('Total Amount (BDT)')
                            ->numeric()
                            ->required()
                            ->prefix('৳')
                            ->helperText('You can override the calculated total for discounts or special pricing'),

                        Forms\Components\Select::make('owner_id')
                            ->label('Customer (Owner)')
                            ->relationship('owner', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('type')
                            ->label('License Type')
                            ->options([
                                'paid'   => 'Paid',
                                'trial'  => 'Trial (Free)',
                                'unpaid' => 'Unpaid (Pending Payment)',
                            ])
                            ->default('paid')
                            ->required(),

                        Forms\Components\Toggle::make('send_email')
                            ->label('Send license keys via email')
                            ->default(true)
                            ->helperText('Will send keys to customer email after creation'),
                    ]),
            ]);
    }

    protected function handleRecordCreation(array $data): void
    {
        $product = \App\Models\Product::find($data['product_id']);
        $licenses = [];

        for ($i = 0; $i < $data['quantity']; $i++) {
            // Pretty key format: PRODUCT-SLUG-XXXX-XXXX-XXXX (e.g., pro-plugin-ABCD-EFGH-IJKL)
            $key = strtoupper($product->slug . '-' . Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4));

            $license = License::create([
                'product_id'        => $data['product_id'],
                'owner_id'          => $data['owner_id'],
                'sold_by_user_id'   => auth()->id(), // Admin who created
                'license_key'       => $key,
                'status'            => in_array($data['type'], ['paid', 'trial']) ? 'active' : 'inactive',
                'type'              => $data['type'],
                'activated_at'      => $data['type'] === 'trial' ? now() : null,
            ]);

            $licenses[] = $license;
        }

        // Record purchase for tracking and reporting
        Purchase::create([
            'buyer_id'      => $data['owner_id'],
            'product_id'    => $data['product_id'],
            'quantity'      => $data['quantity'],
            'total_amount'  => $data['total_amount'],
            'currency'      => 'BDT',
            'status'        => $data['type'] === 'unpaid' ? 'pending' : 'completed',
            'purchased_at'  => now(),
        ]);

        // Email sending placeholder (we'll build the Mailable later)
        if ($data['send_email'] ?? false) {
            // Mail::to(User::find($data['owner_id'])->email)->send(new LicensesGeneratedMail($licenses));
        }

        Notification::make()
            ->title("{$data['quantity']} license(s) created successfully!")
            ->success()
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return LicenseResource::getUrl('index');
    }
}