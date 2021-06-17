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
     * @var int Token order.
     */
    protected $order;

    /**
     * Token constructor.
     *
     * @param string $name
     * @param callable $action
     * @param int $order
     */
    public function __construct(string $name, callable $action, int $order = 10)
    {
        $this->name = $name;
        $this->action = $action;
        $this->order = $order;
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
    public function getOrder(): int
    {
        return $this->order;
    }
}
