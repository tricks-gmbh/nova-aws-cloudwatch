<?php

namespace Tricks\NovaAwsCloudwatch;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class NovaAwsCloudwatch extends Tool
{
    public function boot(): void
    {
        $manifest = File::json(__DIR__.'/../dist/manifest.json');

        Nova::script('nova-aws-cloudwatch', __DIR__.'/../dist/'.$manifest['resources/js/tool.ts']['file']);
        Nova::style('nova-aws-cloudwatch', __DIR__.'/../dist/'.$manifest['style.css']['file']);
    }

    public function menu(Request $request): MenuSection
    {
        return MenuSection::make('Nova Aws Cloudwatch')
            ->path('/nova-aws-cloudwatch')
            ->icon('server');
    }
}
