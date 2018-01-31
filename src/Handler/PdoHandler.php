<?php

namespace uuf6429\WFS\Handler;

class PdoHandler extends BaseHandler
{
    /**
     * @var string[]
     */
    private $drivers;

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        $this->drivers = class_exists(\PDO::class) ? \PDO::getAvailableDrivers() : [];
    }

    /**
     * @inheritdoc
     */
    public function getExamples()
    {
        return array_map(
            function($driver){
                return "pdo-$driver://user:pass@host/database";
            },
            $this->drivers
        );
    }

    /**
     * @inheritdoc
     */
    public function getSuggestions()
    {
        return ['ext-pdo_*' => 'Required for ' . $this->getName() . ' to function.'];
    }

    /**
     * @inheritdoc
     */
    public function supports($dsn)
    {
        $scheme = explode('-', parse_url($dsn, PHP_URL_SCHEME), 2) + [''];

        return $scheme[0] === 'pdo' && in_array($scheme[1], $this->drivers, true);
    }

    /**
     * @inheritdoc
     */
    public function createCheckFunc($dsn)
    {
        $parts = parse_url($dsn);
        $pdoCs = sprintf(
            '%s:host=%s%s%s',
            explode('-', $parts['scheme'], 2)[1],
            $parts['host'],
            isset($parts['port']) ? ";port=${parts['port']}" : '',
            ($db = trim($parts['path'], '/')) ? ";dbname=$db" : ''
        );
        $user = isset($parts['user']) ? $parts['user'] : null;
        $pass = isset($parts['pass']) ? $parts['pass'] : null;

        return function () use ($pdoCs, $user, $pass) {
            return (bool) @new \PDO($pdoCs, $user, $pass, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
        };
    }
}
