<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Cart;
use App\Entity\Contracts\CartItemInterface;
use App\Entity\Contracts\ShopUserInterface;
use App\Enum\CartStatus;
use App\Exception\CartOperationException;
use App\Exception\Contracts\CartOperationExceptionInterface;
use App\Exception\ItemNotFoundException;
use App\Exception\ProductStockDepletedException;
use App\Factory\CartFactory;
use App\Storage\CartSessionStorage;
use App\ValueObject\CouponCode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Lock\LockFactory;

use function assert;
use function sprintf;

final readonly class CartService
{
    public function __construct(
        private CartSessionStorage $cartSessionStorage,
        private CartFactory $cartFactory,
        private EntityManagerInterface $entityManager,
        private ProductStockService $stockService,
        private LockFactory $cartLockFactory,
        private Security $security,
        private int $cartItemMaxCapacity,
    ) {}

    /**
     * @throws ItemNotFoundException
     * @throws ProductStockDepletedException
     */
    public function clearCart(): void
    {
        $cart = $this->getCurrentCart();
        foreach ($cart->getItems() as $item) {
            $cart->removeItem($item);
            $this->stockService->restoreStock($item);
        }

        $this->save($cart);
        $cart->getItems()->clear();
        $this->cartSessionStorage->removeCart();
    }

    public function getCurrentCart(): Cart
    {
        $user = $this->security->getUser();
        assert($user instanceof ShopUserInterface);

        $cart = $this->cartSessionStorage->getCart();
        if (null === $cart) {
            $cart = $this->cartFactory->create($user->getId());
            $this->save($cart);
        }

        return $cart;
    }

    /**
     * Persists the cart in database and session.
     */
    public function save(Cart $cart): void
    {
        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        $this->cartSessionStorage->setCart($cart);
    }

    /** @throws ItemNotFoundException */
    public function removeItem(CartItemInterface $cartItem): void
    {
        $cart = $this->getCurrentCart();
        if (false === $cart->hasItem($cartItem)) {
            throw new ItemNotFoundException('Item already removed');
        }

        $cart->removeItem($cartItem);
    }

    public function confirmCart(): void
    {
        $cart = $this->getCurrentCart();
        $cart->setStatus(CartStatus::CONFIRMED);

        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }

    public function applyCoupon(CouponCode $coupon): void
    {
        $cart = $this->getCurrentCart();
        $cart->applyCoupon($coupon);
    }

    /** @throws CartOperationExceptionInterface */
    public function updateQuantity(int $itemId, int $quantity): void
    {
        if ($quantity > $this->cartItemMaxCapacity) {
            throw new CartOperationException(
                message: sprintf(
                    'maximum number of Items (%s) per product has been exceeded',
                    $this->cartItemMaxCapacity,
                ),
            );
        }

        try {
            $lock = $this->cartLockFactory->createLock('cart_item_update');
            $lock->acquire(true);

            $cart = $this->getCurrentCart();
            $cartItem = $cart->getItemById($itemId);

            $this->removeItem($cartItem);
            $cartItem->updateQuantity($quantity);
            $this->add($cartItem);
            $this->save($cart);

            $lock->release();
        } catch (ItemNotFoundException | ProductStockDepletedException $exception) {
            throw new CartOperationException(message: $exception->getMessage(), previous: $exception);
        }
    }

    /** @throws ItemNotFoundException|ProductStockDepletedException */
    public function add(CartItemInterface $cartItem): void
    {
        $lock = $this->cartLockFactory->createLock('cart_item_add');
        $lock->acquire(true);

        $cart = $this->getCurrentCart();
        $this->stockService->checkStockIsAvailable($cartItem);
        $cart->addItem($cartItem);

        $this->save($cart);
        $lock->release();
    }
}
