<?php

namespace uuf6429\WFS\Exception;

class UnsupportedDSNException extends \RuntimeException
{
    /**
     * @param string $dsn
     */
    public function __construct($dsn)
    {
        parent::__construct("No handler supports the following DSN: $dsn");
    }
}
