<?php

declare(strict_types=1);

namespace App;

interface PromiseInterface
{
    public function then(callable $callback): self;
}
