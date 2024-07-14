<?php

declare(strict_types=1);

namespace App;

final class Promise implements PromiseInterface
{
    private array $thenCallbacks = [];

    public function then(callable $callback): self
    {
        $this->thenCallbacks[] = $callback;

        return $this;
    }

    public function fulfill(mixed $value): void
    {
        $nextValue = $value;

        foreach ($this->thenCallbacks as $callback) {
            $nextValue = $callback($nextValue);
        }
    }
}
