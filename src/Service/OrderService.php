<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Cart;
use App\Entity\Contracts\ShopUserInterface;
use App\Entity\Order;
use App\Event\OrderConfirmedEvent;
use App\Exception\ItemNotFoundException;
use App\Factory\OrderItemFactory;
use App\Pagination\Paginator;
use App\Repository\OrderRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

use function assert;

final readonly class OrderService
{
    public function __construct(
        private WorkflowInterface $orderProcessingStateMachine,
        private Security $security,
        private OrderRepository $orderRepository,
        private AddressBookService $addressBookService,
        private EventDispatcherInterface $eventDispatcher,
        private CalculatorService $calculatorService,
    ) {}

    public function createPending(Cart $cart): Order
    {
        $user = $this->getUser();
        $summary = $this->calculatorService->calculateSummary($cart->getTotalAmount(), $cart->getCoupon());
        $address = $this->addressBookService->getDefaultDeliveryAddress($user->getId());
        if (null === $address) {
            throw new ItemNotFoundException('There is no Address in AddressBook');
        }

        $order = new Order(
            netAmount: $cart->getTotalAmount(),
            userId: $user->getId(),
            shippingCost: $summary->getShipping(),
            total: $summary->getTotal(),
            deliveryAddress: $address,
            bilingAddress: $address,
        );
        $this->orderProcessingStateMachine->getMarking($order);

        $order->applyCoupon($cart->getCoupon());

        foreach ($cart->getItems() as $cartItem) {
            $orderItem = OrderItemFactory::createFromCartItem($cartItem, $order);
            $order->addItem($orderItem);
        }

        $this->orderRepository->save($order);

        return $order;
    }

    public function confirmOrder(Order $order): void
    {
        if (true === $this->orderProcessingStateMachine->can($order, 'to_completed')) {
            $this->orderProcessingStateMachine->apply($order, 'to_completed');
        }

        $this->orderRepository->save($order);

        $orderConfirmedEvent = new OrderConfirmedEvent($order);
        $this->eventDispatcher->dispatch($orderConfirmedEvent);
    }

    public function fetchOrderDetails(int $id): ?Order
    {
        return $this->orderRepository->fetchOrderDetails($id);
    }

    /** @param array<string> $status */
    public function fetchOrders(
        int $userId,
        int $page = 1,
        array $status = [Order::COMPLETED, Order::CANCELLED],
    ): Paginator {
        return $this->orderRepository->fetchOrders($userId, $page, $status);
    }

    private function getUser(): ShopUserInterface
    {
        $user = $this->security->getUser();
        assert($user instanceof ShopUserInterface);

        return $user;
    }
}
