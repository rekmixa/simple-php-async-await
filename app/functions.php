<?php

declare(strict_types=1);

namespace App;

/**
 * @param callable(Task): mixed $coroutine
 * @return Promise
 */
function async(callable $coroutine): Promise
{
    return EventLoop::get()->async($coroutine);
}

function await(Promise $promise): mixed
{
    return EventLoop::get()->await($promise);
}
