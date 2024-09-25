<?php

namespace Tricks\NovaAwsCloudwatch;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\App;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Logger as MonologLogger;
use PhpNexus\Cwh\Handler\CloudWatch;
use Tricks\NovaAwsCloudwatch\Exceptions\IncompleteCloudWatchConfig;

class Logger
{
    public function __construct(protected CloudWatchLogsClient $client)
    {
    }

    /**
     * @param  array<string,int|string|array<string,mixed>>  $config
     * @throws IncompleteCloudWatchConfig
     * @throws Exception
     */
    public function __invoke(array $config): MonologLogger
    {
        $name = (string) $config['name'];
        $streamName = (string) $config['stream_name'];
        $retentionDays = (int) $config['retention'];
        $groupName = (string) $config['group_name'];
        $batchSize = (int) ($config['batch_size'] ?? 10000);

        $logHandler = new CloudWatch($this->client, $groupName, $streamName, $retentionDays, $batchSize);
        $logger = new MonologLogger($name);

        $formatter = $this->resolveFormatter($config);
        $logHandler->setFormatter($formatter);
        $logger->pushHandler($logHandler);

        return $logger;
    }

    /**
     * @param  array<string,int|string|array<string,mixed>>  $config
     * @throws IncompleteCloudWatchConfig
     * @throws BindingResolutionException
     */
    private function resolveFormatter(array $config): LineFormatter|FormatterInterface
    {
        if (! isset($config['formatter'])) {
            return new LineFormatter(
                '%channel%: %level_name%: %message% %context% %extra%',
                null,
                false,
                true
            );
        }

        $formatter = $config['formatter'];

        if (is_string($formatter) && class_exists($formatter)) {
            return App::make($formatter);
        }

        if (is_callable($formatter)) {
            return $formatter($config);
        }

        throw new IncompleteCloudWatchConfig('Formatter is missing for the logs');
    }
}
