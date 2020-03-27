<?php

declare(strict_types=1);

namespace Sendportal\Base\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Sendportal\Base\Interfaces\EmailWebhookServiceInterface;
use Sendportal\Base\Repositories\Campaigns\CampaignTenantRepository;
use Sendportal\Base\Repositories\Campaigns\MySqlCampaignTenantRepository;
use Sendportal\Base\Repositories\Campaigns\PostgresCampaignTenantRepository;
use Sendportal\Base\Repositories\Messages\MessageTenantRepository;
use Sendportal\Base\Repositories\Messages\MySqlMessageTenantRepository;
use Sendportal\Base\Repositories\Messages\PostgresMessageTenantRepository;
use Sendportal\Base\Services\Helper;
use Sendportal\Base\Services\Webhooks\EmailWebhookService;
use Sendportal\Base\Traits\ResolvesDatabaseDriver;

class SendportalAppServiceProvider extends ServiceProvider
{
    use ResolvesDatabaseDriver;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Campaign repository.
        $this->app->bind(CampaignTenantRepository::class, function (Application $app) {
            if ($this->usingPostgres()) {
                return $app->make(PostgresCampaignTenantRepository::class);
            }

            return $app->make(MySqlCampaignTenantRepository::class);
        });

        // Message repository.
        $this->app->bind(MessageTenantRepository::class, function (Application $app) {
            if ($this->usingPostgres()) {
                return $app->make(PostgresMessageTenantRepository::class);
            }

            return $app->make(MySqlMessageTenantRepository::class);
        });

        $this->app->bind(EmailWebhookServiceInterface::class, EmailWebhookService::class);

        $this->app->singleton('sendportal.helper', function () {
            return new Helper();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
