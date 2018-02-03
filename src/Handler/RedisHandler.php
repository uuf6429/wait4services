<?php

namespace uuf6429\WFS\Handler;

class RedisHandler extends BaseHandler
{
    /**
     * @inheritdoc
     */
    public function getExamples()
    {
        return [
            'tcp://10.0.0.1:6379',
            'unix:/path/to/redis.sock',
            'tls://127.0.0.1?ssl[cafile]=private.pem&ssl[verify_peer]=1',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSuggestions()
    {
        return [
            'predis/predis' => 'Required for ' . $this->getName() . ' to function.'
        ];
    }

    /**
     * @inheritdoc
     */
    public function supports($dsn)
    {
        return class_exists(\Predis\Client::class)
            && in_array(
               parse_url($dsn, PHP_URL_SCHEME),
               ['tcp', 'tls', 'unix', 'redis', 'rediss'],
               true
            );
    }

    /**
     * @inheritdoc
     */
    public function createCheckFunc($dsn)
    {
        return function () use ($dsn) {
            $conn = new \Predis\Client($dsn);
            $conn->connect();
            $conn->disconnect();

            return true;
        };
    }
}
