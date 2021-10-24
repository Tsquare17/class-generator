<?php

namespace Tsquare\FileGenerator;

/**
 * Class TokenAction
 * @package Tsquare\FileGenerator
 */
class TokenAction
{
    /**
     * @var string Token name.
     */
    protected $name;

    /**
     * @var callable Token action.
     */
    protected $action;

    /**
     * @var int Token priority.
     */
    protected $priority;

    /**
     * Token constructor.
     *
     * @param string $name
     * @param callable $action
     * @param int $priority
     */
    public function __construct(string $name, callable $action, int $priority = 10)
    {
        $this->name = $name;
        $this->action = $action;
        $this->priority = $priority;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return callable
     */
    public function getAction(): callable
    {
        return $this->action;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }
}
