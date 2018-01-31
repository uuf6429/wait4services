<?php

namespace uuf6429\WFS;

use uuf6429\WFS\Handler\Handler;

class DSNCheck
{
    /**
     * @var string
     */
    private $dsn;

    /**
     * @var Handler
     */
    private $handler;

    /**
     * @var callable
     */
    private $check;

    /**
     * @var null|\Exception
     */
    private $lastError;

    /**
     * @var null|bool
     */
    private $successful = false;

    /**
     * @param string $dsn
     * @param Handler $handler
     */
    public function __construct($dsn, Handler $handler)
    {
        $this->dsn = $dsn;
        $this->handler = $handler;
        $this->check = $handler->createCheckFunc($dsn);
    }

    /**
     * @return string
     */
    public function getDSN()
    {
        return $this->dsn;
    }

    /**
     * @return \Exception|null
     */
    public function getLastError()
    {
        return $this->lastError;
    }

    /**
     * @return Handler
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return (bool) $this->successful;
    }

    /**
     * Runs check function and return itself.
     *
     * @return $this
     */
    public function checkNow()
    {
        $func = $this->check;
        try {
            $this->successful = $func();
        }catch(\Exception $ex){
            $this->lastError = $ex;
        }

        return $this;
    }
}
