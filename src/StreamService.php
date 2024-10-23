<?php

namespace Tricks\NovaAwsCloudwatch;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class StreamService
{
    /** @var array<int,string> */
    protected array $groupsOnly;
    /** @var array<int,string> */
    protected array $groupsExclude;

    public function __construct(protected CloudWatchLogsClient $client)
    {
        $config = config('nova_aws_cloudwatch.groups');
        $this->groupsOnly = $config['only'];
        $this->groupsExclude = $config['exclude'];
    }

    public function getLogGroups(): array
    {
        $data = $this->client
            ->describeLogGroups()
            ->get('logGroups');

        $groups = Arr::where($data, function (array $data) {

            if ($this->groupsOnly !== []) {
                return in_array($data['logGroupName'], $this->groupsOnly);
            }

            if ($this->groupsExclude !== []) {
                return ! in_array($data['logGroupName'], $this->groupsExclude);
            }

            return true;
        });

        return Arr::pluck($groups, 'logGroupName');
    }

    public function getLogStreams(mixed $logGroupName): array
    {
        $data = $this->client
            ->describeLogStreams([
                'logGroupName' => $logGroupName
            ])
            ->get('logStreams');

        return Arr::map($data, fn (array $stream) => [
            'name' => $stream['logStreamName'] ?? '–',
            'timestamps' => [
                'firstEventTimestamp' => Carbon::createFromTimestampMs($stream['firstEventTimestamp'] ?? 0)->toDateTimeString(),
                'lastEventTimestamp' => Carbon::createFromTimestampMs($stream['lastEventTimestamp'] ?? 0)->toDateTimeString(),
                'lastIngestionTime' => Carbon::createFromTimestampMs($stream['lastIngestionTime'] ?? 0)->toDateTimeString(),
            ]
        ]);
    }

    public function getLogStreamContents(string $logGroupName, string $stream): string
    {
        $events = $this->client
            ->getLogEvents([
                'logGroupName' => $logGroupName,
                'logStreamName' => $stream
            ])
            ->get('events');

        return collect($events)
            ->map(fn (array $event) => [
                'timestamp' => Carbon::createFromTimestampMs($event['timestamp'] ?? 0)->toDateTimeString(),
                'message' => $event['message'] ?? null,
            ])
            ->map(fn (array $event) => '['.$event['timestamp'].'] '.$event['message'])
            ->join(PHP_EOL);
    }
}
