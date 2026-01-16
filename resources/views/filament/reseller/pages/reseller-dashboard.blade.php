<x-filament-panels::page>
    <div class="space-y-10">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Reseller Dashboard</h1>
                <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">
                    Manage your reseller account and track your sales performance.
                </p>
            </div>
            <div class="text-right text-sm text-gray-500 dark:text-gray-400">
                Last updated: {{ now()->format('M d, Y') }}
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Quota Card -->
            <x-filament::card class="p-6 bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700 hover:shadow-2xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Quota Remaining</h3>
                    <x-heroicon-o-shield-check class="h-8 w-8 text-amber-500" />
                </div>
                <div class="mt-5 flex items-baseline">
                    <span class="text-5xl font-extrabold text-amber-600 dark:text-amber-400">{{ $this->remainingQuota }}</span>
                    <span class="ml-3 text-2xl font-medium text-gray-500 dark:text-gray-400">/ {{ $this->totalQuota }}</span>
                </div>
                <div class="mt-4 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-500 to-amber-600 h-3 rounded-full transition-all duration-700 ease-out" 
                         style="width: {{ $this->quotaPercentage }}%"></div>
                </div>
                <p class="mt-3 text-sm font-medium text-gray-600 dark:text-gray-400">
                    {{ $this->quotaPercentage }}% used • {{ $this->remainingQuota }} remaining
                </p>
            </x-filament::card>

            <!-- Licenses Sold Card -->
            <x-filament::card class="p-6 bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700 hover:shadow-2xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Licenses Sold</h3>
                    <x-heroicon-o-shopping-cart class="h-8 w-8 text-blue-500" />
                </div>
                <p class="mt-5 text-5xl font-extrabold text-blue-600 dark:text-blue-400">{{ $this->totalLicensesSold }}</p>
                <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">Total licenses assigned to customers</p>
            </x-filament::card>

            <!-- Revenue Card -->
            <x-filament::card class="p-6 bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700 hover:shadow-2xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Total Revenue</h3>
                    <x-heroicon-o-currency-dollar class="h-8 w-8 text-green-500" />
                </div>
                <p class="mt-5 text-5xl font-extrabold text-green-600 dark:text-green-400">${{ number_format($this->totalRevenue, 2) }}</p>
                <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">Earned from completed sales</p>
            </x-filament::card>
        </div>

        <!-- Bulk Purchase & Table Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Bulk Purchase Quick Action -->
            <x-filament::card class="p-6 bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700 lg:col-span-1">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h2>
                <a href="{{ url('/reseller/licenses/create') }}" 
                   class="block w-full bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white text-center py-5 rounded-lg font-medium text-lg transition-all transform hover:scale-105 shadow-md flex items-center justify-center gap-3">
                    <x-heroicon-o-shopping-cart class="h-5 w-5" />
                    Buy Bulk Licenses
                </a>
            </x-filament::card>

            <!-- Assigned Licenses Table -->
            <x-filament::card class="p-6 bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700 lg:col-span-2">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Recently Assigned Licenses</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">License Key</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($this->assignedLicenses as $license)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $license->license_key }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $license->owner->name ?? 'Unassigned' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $license->owner->email ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $license->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                            {{ ucfirst($license->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        <p class="text-lg">No assigned licenses yet</p>
                                        <p class="mt-2">Start by purchasing bulk licenses and assigning them to customers.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-filament::card>
        </div>
    </div>
</x-filament-panels::page>