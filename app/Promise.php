<?php

declare(strict_types=1);

namespace App;

final class Promise
{
    private array $callbacks = [];

    public function then(callable $callback): self
    {
        $this->callbacks[] = $callback;

        return $this;
    }
}
