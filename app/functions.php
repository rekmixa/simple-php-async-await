<?php

declare(strict_types=1);

namespace App;

/**
 * @param callable(Task): mixed $coroutine
 * @return Promise
 */
function async(callable $coroutine): Promise
{
    return Loop::get()->async($coroutine);
}

function await(Promise $promise): mixed
{
    return Loop::get()->await($promise);
}
