<?php

declare(strict_types=1);

namespace InsulinJunkieDe\NightscoutToMqtt;

use Bluerhinos\phpMQTT;
use GuzzleHttp\Client;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Class NightscoutToMqtt
 */
class NightscoutToMqtt
{
    /**
     * @var Client
     */
    protected $guzzleClient;

    /**
     * @var phpMQTT
     */
    protected $phpMqtt;

    /**
     * @var ConfigOptions
     */
    protected $config;

    /**
     * NightscoutToMqtt constructor.
     *
     * @param string $rootDir Root directoty of the application where the .env file is saves
     */
    public function __construct(string $rootDir)
    {
        $this->config = $this->loadConfig($rootDir);
        $this->guzzleClient = $this->buildGuzzleClient();
        $this->phpMqtt = $this->buildMqttClient();
    }

    /**
     * Invoke the class
     */
    public function __invoke()
    {
        $result = $this->fetchLatestNightscoutResult();
        $this->connectMqttClient();
        $this->publishValue($result['sgv']);
        $this->closeMqttClient();
    }

    /**
     * Loads the config from the root directory into ConfigOptions
     *
     * @param string $rootDir Root directory of the application
     *
     * @return ConfigOptions
     */
    private function loadConfig(string $rootDir): ConfigOptions
    {
        (new Dotenv())->load($rootDir . '/.env');
        return new ConfigOptions();
    }

    /**
     * Returns a new GuzzleClient
     *
     * @return Client
     */
    private function buildGuzzleClient()
    {
        return new Client(['verify' => false]);
    }

    /**
     * Returns a new phpMQTT Client
     *
     * @return phpMQTT
     */
    private function buildMqttClient(): phpMQTT
    {
        return new phpMQTT(
            $this->config->getMqttServer(),
            $this->config->getMqttPort(),
            uniqid('svg', true)
        );
    }


    /**
     * Connects to the MQTT-Server
     *
     * @return void
     */
    private function connectMqttClient(): void
    {
        $this->phpMqtt->connect(
            true,
            null,
            $this->config->getMqttUser(),
            $this->config->getMqttPassword()
        );
    }

    /**
     * Publishes the given value to MQTT
     *
     * @param string $value Value to publish
     *
     * @return void
     */
    protected function publishValue(string $value): void
    {
        $this->phpMqtt->publish($this->config->getMqttTopic(), $value, 0);
    }


    /**
     * Closes the connection to the MQTT-Server
     *
     * @return void
     */
    private function closeMqttClient(): void
    {
        $this->phpMqtt->close();
    }

    /**
     * Fetches the latest Nightscout-SGV Entry
     *
     * @return array
     */
    protected function fetchLatestNightscoutResult(): array
    {
        $request = $this->guzzleClient->get($this->config->getNightscoutUrl());
        $values = json_decode((string)$request->getBody(), true, 512, JSON_OBJECT_AS_ARRAY);
        $values = array_reverse($values);

        return array_pop($values);
    }
}