<?php

namespace App\Filament\Admin\Resources\Licenses\Pages;

use App\Filament\Admin\Resources\Licenses\LicenseResource;
use App\Models\License;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use App\Services\Common\CommonServices;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateLicense extends CreateRecord
{
    protected static string $resource = LicenseResource::class;



    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        return DB::transaction(function () use ($data) {

            $product = Product::findOrFail($data['product_id']);
            $quantity = max(1, $data['quantity'] ?? 1);

            $type = $data['type'] ?? 'paid';
            $totalAmount = $data['total_amount'] ?? ($product->price * $quantity);

            if ($data['is_free'] ?? false) {
                $totalAmount = 0;
                $type = 'trial';
            }

            if ($data['later_pay'] ?? false) {
                $type = 'unpaid';
            }

            // Resolve or create user from email
            $email = $data['customer_email'];
            $user = User::where('email', $email)->first();

            if (!$user) {
                $user = CommonServices::createUser($email);
            }

            $purchase = Purchase::create([
                'buyer_id'      => $user->id,
                'product_id'    => $product->id,
                'quantity'      => $quantity,
                'total_amount'  => $totalAmount,
                'currency'      => 'BDT',
                'status'        => ($data['later_pay'] ?? false) ? 'pending' : 'completed',
                'purchased_at'  => now(),
            ]);

            $firstLicense = null;

            for ($i = 0; $i < $quantity; $i++) {
                $key = strtoupper(Str::random(10) . '-' . random_int(1000, 9999) . '-' . Str::random(10));

                $license = License::create([
                    'purchase_id'       => $purchase->id,
                    'product_id'        => $product->id,
                    'owner_id'          => $user->id,
                    'sold_by_user_id'   => CommonServices::currentUser()->id,
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

            // Filament expects ONE model back:
            return $firstLicense; // or return $purchase if your resource is Purchase
        });
    }
}
