<?php

namespace uuf6429\WFS\Exception;

use uuf6429\WFS\DSNCheck;

class TimeoutReachedException extends \RuntimeException
{
    /**
     * @param DSNCheck[] $dsnChecks
     */
    public function __construct($dsnChecks)
    {
        $info = implode(
            "\n",
            array_map(
                function (DSNCheck $dsnCheck) {
                    return sprintf(
                        '- %s ... %s',
                        $dsnCheck->getDSN(),
                        ($ex = $dsnCheck->getLastError()) ? $ex->getMessage() : 'Unknown reason'
                    );
                },
                $dsnChecks
            )
        );

        parent::__construct("The following DSNs failed to go online before timeout:\n$info");
    }
}
