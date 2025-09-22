<?php

namespace App\Providers;

use App\Interfaces\AccountInterface;
use App\Interfaces\Fetches\PropertyFetchInterface;
use App\Interfaces\Fetches\ScanHistoryFetchInterface;
use App\Interfaces\ProfileInterface;
use App\Interfaces\PropertyInterface;
use App\Interfaces\ScanHistoryInterface;
use App\Interfaces\UserInterface;
use App\Services\AccountService;
use App\Services\Fetches\PropertyFetchService;
use App\Services\Fetches\ScanHistoryFetchService;
use App\Services\ProfileService;
use App\Services\PropertyService;
use App\Services\ScanHistoryService;
use App\Services\UserService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserInterface::class, UserService::class);
        $this->app->bind(ProfileInterface::class, ProfileService::class);
        $this->app->bind(AccountInterface::class, AccountService::class);

        // property
        $this->app->bind(PropertyInterface::class, PropertyService::class);
        $this->app->bind(PropertyFetchInterface::class, PropertyFetchService::class);

        // scan history
        $this->app->bind(ScanHistoryInterface::class, ScanHistoryService::class);
        $this->app->bind(ScanHistoryFetchInterface::class, ScanHistoryFetchService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}
