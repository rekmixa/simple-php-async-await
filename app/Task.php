<?php

declare(strict_types=1);

namespace App;

use Fiber;

final class Task
{
    private int $taskId;

    public function __construct(int $taskId)
    {
        $this->taskId = $taskId;
    }

    public function getId(): int
    {
        return $this->taskId;
    }

    public function suspend(): void
    {
        Fiber::suspend();
    }
}
