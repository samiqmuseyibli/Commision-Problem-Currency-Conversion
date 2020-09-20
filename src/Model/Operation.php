<?php

declare (strict_types = 1);

namespace Model;

class Operation
{
    const EU = 1;
    const NOT_EU = 2;

    private $type;

    public function __construct(int $type)
    {
        $this->type = $type;
    }

    public function getType(): int
    {
        return $this->type;
    }
}