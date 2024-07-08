<?php

declare(strict_types=1);

namespace App;

function async(callable $callback): Promise
{
    return new Promise();
}

function await(Promise $promise): mixed
{
    return '';
}
