<?php

declare(strict_types=1);

namespace App;

use SplQueue;
use WeakMap;
use Fiber;

final class EventLoop implements EventLoopInterface
{
    private static ?self $instance = null;

    private int $lastTaskId = 0;
    private SplQueue $tasks;
    private WeakMap $taskMap;
    private WeakMap $promiseMap;
    private WeakMap $fiberMap;

    private function __construct()
    {
        $this->tasks = new SplQueue();
        $this->taskMap = new WeakMap();
        $this->promiseMap = new WeakMap();
        $this->fiberMap = new WeakMap();
    }

    private function __clone(): void
    {
    }

    public static function get(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param callable(Task): mixed $coroutine
     * @return PromiseInterface
     */
    public function async(callable $coroutine): PromiseInterface
    {
        $task = new Task(++$this->lastTaskId);
        $fiber = new Fiber(static function () use ($coroutine, $task) {
            Fiber::suspend();

            return $coroutine($task);
        });

        $fiber->start();

        $this->tasks->enqueue($task);
        $promise = new Promise();
        $this->promiseMap[$task] = $promise;
        $this->taskMap[$promise] = $task;
        $this->fiberMap[$task] = $fiber;

        return $promise;
    }

    public function await(PromiseInterface $promise): mixed
    {
        /** @var Task $task */
        $task = $this->taskMap[$promise];

        return $this->run($task->getId());
    }

    /**
     * @param int|null $awaitingTaskId
     * @return void|mixed
     */
    public function run(?int $awaitingTaskId = null)
    {
        while (!$this->tasks->isEmpty()) {
            /** @var Task $task */
            $task = $this->tasks->dequeue();
            /** @var Fiber $fiber */
            $fiber = $this->fiberMap[$task];

            if ($fiber->isSuspended() && !$fiber->isTerminated()) {
                $fiber->resume();

                if ($fiber->isTerminated()) {
                    /** @var Promise $promise */
                    $promise = $this->promiseMap[$task];
                    $promise->fulfill($fiber->getReturn());

                    if ($awaitingTaskId !== null && $task->getId() === $awaitingTaskId) {
                        return $fiber->getReturn();
                    }
                }
            }

            if (!$fiber->isTerminated()) {
                $this->tasks->enqueue($task);
            }
        }
    }
}
