<?php

declare(strict_types=1);

namespace App;

interface EventLoopInterface
{
    /**
     * @param callable(Task): mixed $coroutine
     * @return PromiseInterface
     */
    public function async(callable $coroutine): PromiseInterface;

    public function await(Promise $promise): mixed;

    /**
     * @param int|null $awaitingTaskId
     * @return void|mixed
     */
    public function run(?int $awaitingTaskId = null);
}
