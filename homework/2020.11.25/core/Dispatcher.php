<?php

namespace app\core;

use app\helpers\StringsHelper;

/**
 * Class Dispatcher
 * @package app\core
 */
final class Dispatcher
{
    private const DEFAULT_CONTROLLER_NAME = 'index';
    private const DEFAULT_ACTION_NAME = 'index';

    /**
     * @var string
     */
    private string $requestAddress;

    /**
     * @var string
     */
    private string $addressSeparator;

    /**
     * @var string
     */
    private string $controllerName;

    /**
     * @var string
     */
    private string $actionName;

    /**
     * @var array
     */
    private array $requestParams;

    /**
     * Dispatcher constructor.
     * @param string $requestAddress
     * @param string $addressSeparator
     */
    public function __construct(string $requestAddress, string $addressSeparator = '/')
    {
        $this->addressSeparator = $addressSeparator;
        $this->setAddressWithoutGetParams($requestAddress);
        $this->dispatch();
    }

    /**
     * Returns controller's current name
     * @return string
     */
    public function getControllerName(): string
    {
        return $this->controllerName;
    }

    /**
     * Returns action's current name
     * @return string
     */
    public function getActionName(): string
    {
        return $this->actionName;
    }

    /**
     * Returns param's current array
     * @return array
     */
    public function getRequestParams(): array
    {
        return $this->requestParams;
    }

    /**
     * Returns complete request's info
     * @return array
     */
    public function getRequestInfo(): array
    {
        return [
            'controllerName' => $this->controllerName,
            'actionName' => $this->actionName,
            'requestParams' => $this->requestParams,
        ];
    }
    
    /**
     * Subtracts get-parameters from request string
     * @param string $address
     */
    private function setAddressWithoutGetParams(string $address): void
    {
        $paramsStart = strpos($address, '?');
        $this->requestAddress = ($paramsStart !== false) ? substr($address, 0, $paramsStart) : $address;
    }

    /**
     * Splits request address into independent pieces: controllerName, actionName, requestParams
     */
    private function dispatch(): void
    {
        $parts = explode($this->addressSeparator,
                        StringsHelper::customTrim($this->requestAddress, $this->addressSeparator));

        $this->controllerName = array_shift($parts) ?: self::DEFAULT_CONTROLLER_NAME;
        $this->actionName = array_shift($parts) ?: self::DEFAULT_ACTION_NAME;

        $this->setParams($parts);
    }

    /**
     * Sets request parameters array
     * @param array $parts
     */
    private function setParams(array $parts): void
    {
        $keys = [];
        $values = [];

        foreach ($parts as $index => $value) {
            ($index % 2 === 0) ? ($keys[] = $value) : ($values[] = $value);
        }

        (count($keys) > count($values)) ? ($values[] = null) : null;

        $this->requestParams = array_merge(array_combine($keys, $values), $_GET);
    }
}
