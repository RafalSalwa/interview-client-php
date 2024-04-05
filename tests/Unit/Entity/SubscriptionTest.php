<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Subscription;
use App\Entity\SubscriptionPlan;
use App\Enum\SubscriptionTier;
use App\Tests\Helpers\SubscriptionPlanTrait;
use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Subscription::class)]
#[UsesClass(className: SubscriptionPlan::class)]
#[UsesClass(className: SubscriptionTier::class)]
final class SubscriptionTest extends TestCase
{
    use SubscriptionPlanTrait;

    protected function setUp(): void
    {
        // TODO: Change the autogenerated stub
        parent::setUp();
    }

    public function testConstructor(): void
    {
        $userId = 1;
        $plan = $this->getHelperSubscriptionPlan();

        $subscription = new Subscription($userId, $plan);

        $subscription->setId(1);
        $this->assertSame(1, $subscription->getId());
        $this->assertSame($userId, $subscription->getUserId());
        $this->assertSame($plan, $subscription->getPlan());
        $this->assertSame(SubscriptionTier::Freemium, $subscription->getTier());
        $this->assertSame(SubscriptionTier::Freemium->value(), $subscription->getRequiredLevel());
        $this->assertTrue($subscription->isActive());

        $dateTimeImmutable = new DateTimeImmutable();
        $this->assertEqualsWithDelta(
            $dateTimeImmutable->getTimestamp(),
            $subscription->getCreatedAt()->getTimestamp(),
            1,
        );
        $this->assertEqualsWithDelta(
            $dateTimeImmutable->getTimestamp(),
            $subscription->getStartsAt()->getTimestamp(),
            1,
        );
        $this->assertEqualsWithDelta(
            $dateTimeImmutable->add(new DateInterval('P30D'))->getTimestamp(),
            $subscription->getEndsAt()->getTimestamp(),
            1,
        );
    }

    public function testIsActive(): void
    {
        $subscription = new Subscription(1, $this->getHelperSubscriptionPlan());

        $this->assertTrue($subscription->isActive());

        $subscription->setIsActive(false);

        $this->assertFalse($subscription->isActive());
    }

    public function testSettersAndGetters(): void
    {
        $subscription = new Subscription(1, $this->getHelperSubscriptionPlan());

        $dateTimeImmutable = new DateTimeImmutable('2022-01-01');

        $subscription->setCreatedAt($dateTimeImmutable);
        $subscription->setIsActive(false);

        $this->assertSame($dateTimeImmutable, $subscription->getCreatedAt());
        $this->assertFalse($subscription->isActive());
    }
}