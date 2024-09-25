<?php

namespace Tricks\NovaAwsCloudwatch;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;

class ToolServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/nova_aws_cloudwatch.php', 'nova_aws_cloudwatch'
        );
    }

    public function boot(): void
    {
        $this->app->booted(function () {
            $this->routes();
        });

        $this->publishes([
            __DIR__.'/../config/nova_aws_cloudwatch.php' => config_path('nova_aws_cloudwatch.php')
        ], 'nova-aws-cloudwatch-config');

        $this->app->singleton(CloudWatchLogsClient::class, function (Application $app) {
            $cloudwatchConfig = config('logging.channels.cloudwatch');
            return new CloudWatchLogsClient([
                'region' => $cloudwatchConfig['region'],
                'version' => $cloudwatchConfig['version'],
                'credentials' => $cloudwatchConfig['credentials'],
                'endpoint' => $cloudwatchConfig['endpoint'],
            ]);
        });
    }

    protected function routes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        $this->loadRoutesFrom(__DIR__.'/../routes/inertia.php');
    }

    public function menu(): MenuSection
    {
        return MenuSection::make('Cloudwatch Logs')
            ->path('/nova-aws-cloudwatch')
            ->icon('server');
    }
}
