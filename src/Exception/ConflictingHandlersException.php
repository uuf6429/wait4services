<?php

namespace uuf6429\WFS\Exception;

use uuf6429\WFS\Handler\Handler;

class ConflictingHandlersException extends \RuntimeException
{
    /**
     * @param Handler[] $handlers
     * @param string $dsn
     */
    public function __construct($handlers, $dsn)
    {
        parent::__construct(
            sprintf(
                'Several handlers (%s) support the following DSN: %s',
                array_map(
                    function (Handler $handler) {
                        return $handler->getName();
                    },
                    $handlers
                ),
                $dsn
            )
        );
    }
}
