<?php

namespace app\core;

use app\controllers\IndexController;

final class Route
{
    private const DEFAULT_CONTROLLER_NAME = 'index';
    private const DEFAULT_ACTION_NAME = 'index';
    private const CONTROLLER_ACTION_PATTERN = '/^([a-z0-9]+[\-]?[a-z0-9]+)+$/';

    /**
     * @var mixed|string
     */
    private string $requestControllerName;

    /**
     * @var string
     */
    private string $controllerName;

    /**
     * @var mixed|string
     */
    private string $requestActionName;

    /**
     * @var string
     */
    private string $actionName;

    /**
     * @var array|mixed
     */
    private array $requestParams;

    /**
     * Route constructor.
     * @param array $requestParams
     */
    public function __construct(array $requestParams)
    {

        $this->requestControllerName = $this->checkRouteElement($requestParams['controllerName'])
                                        ? $requestParams['controllerName'] : self::DEFAULT_CONTROLLER_NAME;

        $this->requestActionName = $this->checkRouteElement($requestParams['actionName'])
                                    ? $requestParams['actionName'] : self::DEFAULT_ACTION_NAME;

        $this->requestParams = $requestParams['requestParams'];

        $this->setControllerName();

        $this->setActionName();

        $this->makeAction();
    }

    /**
     * Checks if controller/action accords to pattern
     * @param string $routeItem
     * @return bool
     */
    private function checkRouteElement(string $routeItem): bool
    {
        return preg_match(self::CONTROLLER_ACTION_PATTERN, $routeItem);
    }

    /**
     * Formats controller/action name in order to pattern
     * @param string $name
     * @param string $delimiter
     * @return string
     */
    private function getProcessedName(string $name, string $delimiter = '-'): string
    {
        $processedName = ucwords($name, $delimiter);
        $processedName = str_replace('-', '', $processedName);

        return $processedName;
    }

    /**
     * Sets controller's formatted name
     */
    private function setControllerName(): void
    {
        $processedRequestControllerName = $this->getProcessedName($this->requestControllerName);

        $this->controllerName = "{$processedRequestControllerName}Controller";
    }

    /**
     * Sets action's formatted name
     */
    private function setActionName(): void
    {
        $processedRequestActionName = $this->getProcessedName($this->requestActionName);

        $this->actionName = "action{$processedRequestActionName}";
    }

    /**
     * Creates controller's instance and then makes selected action
     */
    private function makeAction(): void
    {

        $controllerClassPath = __DIR__ . "/../controllers/{$this->controllerName}.php";

        if (file_exists($controllerClassPath)) {
            $controllerClass = "app\\controllers\\{$this->controllerName}";
        } else {
            $controllerClass = IndexController::class;
        }

        $controller = new $controllerClass();
        $action = $this->actionName;

        if (method_exists($controller, $action)) {
            $controller->$action($this->requestParams);
        } else {
            $action = 'actionIndex';
            $controller->$action($this->requestParams);
        }
    }
}
