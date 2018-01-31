<?php

require_once __DIR__ . '/../vendor/autoload.php';

$configFile = __DIR__ . '/../composer.json';
$config = json_decode(file_get_contents($configFile), false);
$handlers = \uuf6429\WFS\HandlerManager::getInstance()->getHandlers();

$config->suggest = (object)[];
foreach ($handlers as $handler) {
    foreach ($handler->getSuggestions() as $package => $reason) {
        if (property_exists($config->suggest, $package)) {
            $config->suggest->$package .= "\n".$reason;
        } else {
            $config->suggest->$package = $reason;
        }
    }
}

file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
