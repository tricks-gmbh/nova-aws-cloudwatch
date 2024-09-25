<?php

namespace Tricks\NovaAwsCloudwatch\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Nova\Http\Requests\NovaRequest;
use Tricks\NovaAwsCloudwatch\StreamService;

class InertiaController extends Controller
{

    public function index(NovaRequest $request, StreamService $service): Response
    {
        return Inertia::render('NovaAwsCloudwatchGroups')
            ->with('groups', $service->getLogGroups());
    }

    public function showGroup(NovaRequest $request, StreamService $service, string $group): Response
    {
        abort_if($this->groupNotAllowed($group), 404);

        $streams = $service->getLogStreams($group);

        return Inertia::render('NovaAwsCloudwatchStreams')
            ->with('group', $group)
            ->with('streams', $streams);
    }

    public function showStream(NovaRequest $request, StreamService $service, string $group, string $stream): Response
    {
        abort_if($this->groupNotAllowed($group), 404);

        $streamContent = $service->getLogStreamContents($group, $stream);

        return Inertia::render('NovaAwsCloudwatchStreamContent')
            ->with('group', $group)
            ->with('stream', $stream)
            ->with('streamContent', $streamContent);
    }

    public function groupNotAllowed(string $group): bool
    {
        $onlyRules = config('nova_aws_cloudwatch.groups.only');

        if ($onlyRules !== []) {
            return in_array($group, $onlyRules);
        }

        $excludeRules = config('nova_aws_cloudwatch.groups.exclude');

        if ($excludeRules !== []) {
            return ! in_array($group, $excludeRules);
        }

        return false;
    }
}
