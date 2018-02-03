<?php

namespace uuf6429\WFS\Handler;

class AMQPLibHandler extends BaseHandler
{
    /**
     * @inheritdoc
     */
    public function getExamples()
    {
        return [
            'amqp://guest:guest@client1/development'
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSuggestions()
    {
        return [
            'php-amqplib/php-amqplib' => 'Required for ' . $this->getName() . ' to function.'
        ];
    }

    /**
     * @inheritdoc
     */
    public function supports($dsn)
    {
        return class_exists(\PhpAmqpLib\Connection\AMQPStreamConnection::class)
            && in_array(
               parse_url($dsn, PHP_URL_SCHEME),
               ['amqp', 'rabbitmq'],
               true
            );
    }

    /**
     * @inheritdoc
     */
    public function createCheckFunc($dsn)
    {
        $parts = parse_url($dsn);
        $parts['port'] = !empty($parts['port']) ? $parts['port'] : 5672;
        $parts['user'] = !empty($parts['user']) ? $parts['user'] : '';
        $parts['path'] = !empty($parts['path']) ? $parts['path'] : '/';

        return function () use ($parts) {
            $conn = new \PhpAmqpLib\Connection\AMQPStreamConnection(
                $parts['host'],
                $parts['port'],
                $parts['user'],
                $parts['pass'],
                $parts['path']
            );
            $conn->close();

            return true;
        };
    }
}
