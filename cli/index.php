<?php

declare(strict_types=1);

use App\EventLoop;

use App\Task;

use Clio\Console;

use function App\async;
use function App\await;

require __DIR__ . '/../config/bootstrap.php';

async(static function (Task $task): void {
    for ($i = 0; $i < 3; $i++) {
        Console::output('Task #' . $task->getId() . ' iteration ' . $i);
        $task->suspend();
    }
});

await(async(static function (Task $task): void {
    for ($i = 0; $i < 3; $i++) {
        Console::output('Task #' . $task->getId() . ' iteration ' . $i);
        $task->suspend();
    }
}));

$promise = async(static function (): array {
    return [
        'value' => 123,
    ];
});

$promise
    ->then(static function (array $data): int {
        Console::output('Data: ' . var_export($data, true));

        return $data['value'];
    })
    ->then(static function (int $value): void {
        Console::output('Value: ' . $value);
    });

$result = await($promise);

Console::output('Awaited task result: ' . var_export($result, true));

async(static function (Task $task): void {
    Console::output('Task #' . $task->getId());
});

EventLoop::get()->run();
