<?php

namespace App\Filament\Reseller\Pages;

use Filament\Pages\Page;
use App\Models\License;
use App\Models\Purchase;
use App\Models\ResellerQuota;
use App\Services\Common\CommonServices;
use Illuminate\Support\Facades\Auth;

use BackedEnum;

class ResellerDashboard extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-key';
    protected string $view = 'filament.reseller.pages.reseller-dashboard';  //
    // Expose data to Blade


    public function getAssignedLicensesProperty()
    {
        return License::where('sold_by_user_id', CommonServices::currentUser()->id)
            ->with('owner')
            ->latest()
            ->limit(10)
            ->get();
    }  

    public function getTotalLicensesSoldProperty()
{
    return License::where('sold_by_user_id', Auth::id())->count();
}

public function getTotalRevenueProperty()
{
    return Purchase::where('buyer_id', Auth::id())
        ->where('status', 'completed')
        ->sum('total_amount') ?? 0;
}

public function getTotalQuotaProperty()
{
    $quota = ResellerQuota::where('reseller_id', Auth::id())->first();
    return $quota ? $quota->free_license_limit : 0;
}

public function getRemainingQuotaProperty()
{
    $quota = ResellerQuota::where('reseller_id', Auth::id())->first();
    return $quota ? max(0, $quota->free_license_limit - $quota->used_free_licenses) : 0;
}

public function getQuotaPercentageProperty()
{
    $quota = ResellerQuota::where('reseller_id', Auth::id())->first();
    if (!$quota || $quota->free_license_limit == 0) return 0;
    return round(($quota->used_free_licenses / $quota->free_license_limit) * 100, 0);
}
}
