<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\CartItemInterface;
use App\Entity\Product;
use App\Entity\ProductCartItem;
use App\Entity\SubscriptionPlanCartItem;
use App\Exception\ItemNotFoundException;
use App\Exception\ProductStockDepletedException;
use App\Exception\TooManySubscriptionsException;
use App\Factory\CartFactory;
use App\Factory\CartItemFactory;
use App\Storage\CartSessionStorage;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Lock\LockFactory;

class CartService
{
    public function __construct(
        private readonly CartSessionStorage $cartSessionStorage,
        private readonly CartFactory $cartFactory,
        private readonly EntityManagerInterface $entityManager,
        private readonly CartItemFactory $cartItemFactory,
        private readonly ProductStockService $productStockService,
        private readonly LockFactory $cartLockFactory,
    ) {
    }

    public function clearCart()
    {
        $cart = $this->getCurrentCart();
        foreach ($cart->getItems() as $item) {
            $cart->removeItem($item);
            $this->productStockService->restoreStock($item);
            $this->save($cart);
        }
        $cart->getItems()->clear();
        $this->cartSessionStorage->removeCart();
    }

    public function getCurrentCart(): Cart
    {
        $cart = $this->cartSessionStorage->getCart();
        if (!$cart) {
            $cart = $this->cartFactory->create();
        }
        return $cart;
    }

    /**
     * Persists the cart in database and session.
     */
    public function save(Cart $cart = null): void
    {
        if (!$cart) {
            $cart = $this->getCurrentCart();
        }
        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        $this->cartSessionStorage->setCart($cart);
    }

    public function confirmCart()
    {
        $cart = $this->getCurrentCart();
        $cart->setStatus(Cart::STATUS_CONFIRMED);
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }

    public function setDefaultDeliveryAddress(int $deliveryAddressId)
    {
        $this->cartSessionStorage->setDeliveryAddressId($deliveryAddressId);
    }

    public function getDefaultDeliveryAddressId(): ?int
    {
        return $this->cartSessionStorage->getDeliveryAddressId();
    }

    /**
     * @throws ProductStockDepletedException
     */
    public function addProduct(Product $product): void
    {
        $cart = $this->getCurrentCart();
        $item = $this->makeCartItem($product);
        $this->productStockService->changeStock($product, Product::STOCK_DECREASE, 1);


        $cart->addItem($item);

        $this->save($cart);
    }

    /**
     * @throws ItemNotFoundException
     */
    public function makeCartItem($entity): CartItem|SubscriptionPlanCartItem|ProductCartItem
    {
        return $this->cartItemFactory->createCartItem($entity);
    }

    /**
     * @throws ProductStockDepletedException
     * @throws Exception
     * @throws TooManySubscriptionsException
     * @throws ItemNotFoundException
     */
    public function add(CartItemInterface $item): void
    {
        $lock = $this->cartLockFactory->createLock('cart_item_add');
        $lock->acquire(true);

        $this->productStockService->checkStockIsAvailable($item);

        $this->checkSubscriptionsCount($item);

        $cart = $this->getCurrentCart();
        $cart->addItem($item);
        $this->save($cart);

        $this->productStockService->changeStock($item, Product::STOCK_DECREASE, 1);

        $lock->release();
    }

    /**
     * @throws TooManySubscriptionsException
     */
    public function checkSubscriptionsCount(CartItemInterface $item): void
    {
        $cart = $this->getCurrentCart();

        if ($item instanceof SubscriptionPlanCartItem && $cart->itemTypeExists($item)) {
            throw new TooManySubscriptionsException("You can have only one subscription in cart");
        }
    }

    public function removeItemIfExists(CartItem $item)
    {
        $cart = $this->getCurrentCart();
        
        if ($cart->getItems()->contains($item)) {
            $cart->getItems()->removeElement($item);
        }
    }

}
