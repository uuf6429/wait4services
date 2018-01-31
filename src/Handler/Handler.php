<?php

namespace uuf6429\WFS\Handler;

interface Handler
{
    /**
     * Returns a human-readable name for this handler.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns a few example DSNs supported by this handler.
     *
     * @return string[]
     */
    public function getExamples();

    /**
     * Returns a list of composer packages required or useful for this handler.
     * The array key is the package, whereas the value is a reason for the suggestions.
     *
     * @return array
     */
    public function getSuggestions();

    /**
     * Returns whether the specified DSN is supported by this handler or not.
     *
     * @param string $dsn
     *
     * @return bool
     */
    public function supports($dsn);

    /**
     * Returns a function which when called, it should return true if the specified DSN is reachable and functional.
     * The function is allowed to throw exceptions, whose message is displayed later on if needed.
     *
     * @param string $dsn
     *
     * @return callable
     */
    public function createCheckFunc($dsn);
}
