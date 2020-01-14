<?php

declare(strict_types=1);

namespace InsulinJunkieDe\NightscoutToMqtt;

/**
 * Class ConfigOptions
 */
class ConfigOptions
{

    /**
     * @var string
     */
    private $nightscoutUrl;

    /**
     * @var string
     */
    private $mqttServer;

    /**
     * @var string
     */
    private $mqttPort;

    /**
     * @var string
     */
    private $mqttUser;

    /**
     * @var string
     */
    private $mqttPassword;

    /**
     * @var   string
     */
    private $mqttTopic;

    /**
     * ConfigOptions constructor.
     */
    public function __construct()
    {
        $this->checkAndFill('NIGHTSCOUT_URL', 'nightscoutUrl');
        $this->checkAndFill('MQTT_SERVER', 'mqttServer');
        $this->checkAndFill('MQTT_PORT', 'mqttPort');
        $this->checkAndFill('MQTT_USER', 'mqttUser');
        $this->checkAndFill('MQTT_PASSWORD', 'mqttPassword');
        $this->checkAndFill('MQTT_TOPIC', 'mqttTopic');
    }

    /**
     * Fills in the values from ENV to the given property
     *
     * @param string $envName Name of the ENV-var
     * @param string $property Internal Propertyname to save to
     */
    protected function checkAndFill(string $envName, string $property)
    {
        $this->$property = $_ENV[$envName];
    }

    /**
     * Gets Nightscout Url
     *
     * @return string
     */
    public function getNightscoutUrl(): string
    {
        return $this->nightscoutUrl;
    }

    /**
     * Gets MQTT server
     *
     * @return string
     */
    public function getMqttServer(): string
    {
        return $this->mqttServer;
    }

    /**
     * Gets MQTT port
     *
     * @return string
     */
    public function getMqttPort(): string
    {
        return $this->mqttPort;
    }

    /**
     * Gets MQTT username
     *
     * @return string
     */
    public function getMqttUser(): string
    {
        return $this->mqttUser;
    }

    /**
     * Gets MQTT password
     *
     * @return string
     */
    public function getMqttPassword(): string
    {
        return $this->mqttPassword;
    }

    /**
     * Gets MQTT topic
     *
     * @return string
     */
    public function getMqttTopic(): string
    {
        return $this->mqttTopic;
    }
}