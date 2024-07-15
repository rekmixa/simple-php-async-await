<?php

declare(strict_types=1);

use App\EventLoop;

use App\Task;

use Clio\Console;

use function App\async;

require __DIR__ . '/../config/bootstrap.php';


final class CreateOrderCase
{
    // Более "жизненный пример"
    // ...

    public function create(Customer $customer, ProductId $product, OrderDetails $details): Order
    {
        $order = new Order($product, $details);
        $this->orderRepository->persist($order);
        $this->entityManager->flush();

        // откладываем отправку уведомления клиенту для того, чтобы клиентский код
        // мог незамедлительно начать работу с только что созданным заказом
        // задержка выполнения для отправки уведомлений в данном случае нецелесообразна
        async(fn() => $this->notifier->notify($customer, new OrderCreated()));
        async(fn() => $this->crm->createTask(new NeedToHandleOrder($order->getId())));

        return $order;
    }
}


async(static function (Task $task): void {
    Console::output('Task #' . $task->getId());
});

EventLoop::get()->run();
