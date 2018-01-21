<?php

namespace uuf6429\WFS\Handler;

class CurlHandler implements Handler
{
    public function getName()
    {
        return 'CurlHandler';
    }

    public function getExamples()
    {
        return [
            'http://user:pass@host/test?a=b',
            'https://test.com/a/dir/huh.asp',
        ];
    }

    public function supports($dsn)
    {
        return function_exists('curl_init')
            ? in_array(parse_url($dsn, PHP_URL_SCHEME), ['http', 'https'])
            : [];
    }

    public function createCheckFunc($dsn)
    {
        return function () use ($dsn) {
            $ch = curl_init();

            try {
                curl_setopt($ch, CURLOPT_URL, $dsn);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko)');
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                $response = curl_exec($ch);

                if (!$response && ($err = curl_errno($ch))){
                    throw new \RuntimeException("CurlHandler error $err: " . curl_error($ch));
                }

                $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if ($code >= 400) {
                    throw new \RuntimeException("Error $code, response: " . $response);
                }

                return true;
            } finally {
                curl_close($ch);
            }
        };
    }
}