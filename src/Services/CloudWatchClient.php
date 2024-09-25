<?php

namespace Tricks\NovaAwsCloudwatch\Services;

use Aws\CloudWatchLogs\CloudWatchLogsClient;

class CloudWatchClient
{
    public CloudWatchLogsClient $client;

    public function __construct()
    {
        $cloudwatchConfig = config('logging.channels.cloudwatch');

        $this->client = new CloudWatchLogsClient([
            'region' => $cloudwatchConfig['region'],
            'version' => $cloudwatchConfig['version'],
            'credentials' => $cloudwatchConfig['credentials'],
            'endpoint' => $cloudwatchConfig['endpoint'],
        ]);
    }
}
