<?php

namespace Codetechnl\NovaAwsCloudwatch;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Pagevamp\Exceptions\IncompleteCloudWatchConfig;
use PhpNexus\Cwh\Handler\CloudWatch;

class Logger
{
    public function __construct(private ?Application $app = null)
    {
        if ($this->app === null) {
            $this->app = App::getInstance();
        }
    }

    /**
     * @param  array<string,int|string|array<string,mixed>>  $config
     * @throws BindingResolutionException
     * @throws IncompleteCloudWatchConfig
     * @throws Exception
     */
    public function __invoke(array $config): \Monolog\Logger
    {
        $cwClient = new CloudWatchLogsClient($this->getCredentials($config));

        $name = (string) $config['name'];
        $streamName = (string) $config['stream_name'];
        $retentionDays = (int) $config['retention'];
        $groupName = (string) $config['group_name'];
        $batchSize = (int) ($config['batch_size'] ?? 10000);

        $logHandler = new CloudWatch($cwClient, $groupName, $streamName, $retentionDays, $batchSize);
        $logger = new \Monolog\Logger($name);

        $formatter = $this->resolveFormatter($config);
        $logHandler->setFormatter($formatter);
        $logger->pushHandler($logHandler);

        return $logger;
    }

    /**
     * @param  array<string,int|string|array<string,mixed>>  $config
     * @return array<string,string|array<string,mixed>>
     * @throws IncompleteCloudWatchConfig
     */
    protected function getCredentials(array $config): array
    {

        $awsCredentials = [
            'region' => (string) $config['region'],
            'version' => (string) $config['version'],
        ];

        if (isset($config['credentials']['key'])) {
            $awsCredentials['credentials'] = $config['credentials'];
        }

        if (isset($config['endpoint'])) {
            $awsCredentials['endpoint'] = (string) $config['endpoint'];
        }

        return $awsCredentials;
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
            return $this->app?->make($formatter);
        }

        if (is_callable($formatter)) {
            return $formatter($config);
        }

        throw new IncompleteCloudWatchConfig('Formatter is missing for the logs');
    }
}
