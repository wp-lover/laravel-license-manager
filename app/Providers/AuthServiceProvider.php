<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

// Import your models and policies
use App\Models\Product;
use App\Models\Purchase;
use App\Models\License;
use App\Models\Payment;
use App\Models\ResellerQuota;

use App\Policies\ProductPolicy;
use App\Policies\PurchasePolicy;
use App\Policies\LicensePolicy;
use App\Policies\PaymentPolicy;
use App\Policies\ResellerQuotaPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
        Purchase::class => PurchasePolicy::class,
        License::class => LicensePolicy::class,
        Payment::class => PaymentPolicy::class,
        ResellerQuota::class => ResellerQuotaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
