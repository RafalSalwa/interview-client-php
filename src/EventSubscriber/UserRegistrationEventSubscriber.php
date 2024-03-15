<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\UserConfirmedEvent;
use App\Event\UserRegisteredEvent;
use App\Event\UserVerificationCodeRequestEvent;
use App\Exception\SubscriptionPlanNotFoundException;
use App\Repository\SubscriptionPlanRepository;
use App\Repository\SubscriptionRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class UserRegistrationEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected SubscriptionRepository $subscriptionRepository,
        protected SubscriptionPlanRepository $subscriptionPlanRepository,
    ) {}

    /** @return array<class-string,string> */
    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisteredEvent::class => 'onRegistrationCompleted',
            UserVerificationCodeRequestEvent::class => 'onVerificationCodeRequest',
            UserConfirmedEvent::class => 'onConfirmed',
        ];
    }

    /** @throws SubscriptionPlanNotFoundException */
    public function onRegistrationCompleted(UserRegisteredEvent $userRegisteredEvent): void
    {
        //        $user = $userRegisteredEvent->getEmail();
        //
        //        $subscriptionPlan = $this->subscriptionPlanRepository->findOneBy(['name' => 'freemium']);
        //        if (null === $subscriptionPlan) {
        //            throw new SubscriptionPlanNotFoundException('Subscription plan not found');
        //        }
        //
        //        $subscription = new Subscription();
        //        $subscription->setUserId($user->getId());
        //        $subscription->setPlan($subscriptionPlan);
        //
        //        $this->subscriptionRepository->save($subscription);
    }

    public function onVerificationCodeRequest(UserVerificationCodeRequestEvent $event): void
    {
    }

    public function onConfirmed(UserConfirmedEvent $event): void
    {
        $plan = $this->subscriptionPlanRepository->createFreemiumPlan();
        $this->subscriptionRepository->assignSubscription($event->getUserId(), $plan);
    }
}