<?php

namespace uuf6429\WFS;

use uuf6429\WFS\Handler;

class HandlerManager
{
    /**
     * @var self
     */
    private static $instance;

    /**
     * @var Handler\Handler[]
     */
    private $handlers = [];

    private function __construct()
    {
    }

    /**
     * We don't like singletons, but this is the safest way to extend WFS from a Composer/autoload environment.
     */
    public static function getInstance()
    {
        return self::$instance ?: (self::$instance = new self());
    }

    /**
     * Register a new handler.
     *
     * @param Handler\Handler|Handler\Handler[] $handler
     */
    public function register($handler)
    {
        if (!is_array($handler)) {
            $handler = [$handler];
        }

        $this->handlers = array_merge($this->handlers, $handler);
    }

    /**
     * @return Handler\Handler[]
     */
    public function getHandlers()
    {
        return $this->handlers;
    }
}

HandlerManager::getInstance()->register([
    new Handler\CurlHandler(),
    new Handler\PdoHandler(),
]);
