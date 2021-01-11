<?php

namespace Imunew\Laravel\Database\Queries\Console;

use Illuminate\Console\Command;
use InvalidArgumentException;

/**
 * Trait GetOption
 * @package Imunew\Laravel\Database\Queries\Console
 *
 * @mixin Command
 */
trait GetOption
{
    /**
     * @param string $name
     * @param bool $required
     * @param array|bool|string|null $default
     * @return array|bool|string|null
     * @throws InvalidArgumentException
     */
    private function getOption(string $name, bool $required = false, $default = null)
    {
        $option = $this->option($name);
        if ($required && is_null($option)) {
            $message = "--$name must not be empty.";
            throw new InvalidArgumentException($message);
        }
        return (is_null($option) && !is_null($default)) ? $default : $option;
    }
}
