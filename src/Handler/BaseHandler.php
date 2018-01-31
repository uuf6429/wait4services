<?php

namespace uuf6429\WFS\Handler;

abstract class BaseHandler implements Handler
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * @inheritdoc
     */
    public function getExamples()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getSuggestions()
    {
        return [];
    }
}
