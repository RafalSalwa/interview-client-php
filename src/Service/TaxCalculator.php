<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use DivisionByZeroError;
use function bcdiv;
use function bcsub;
use function number_format;

class TaxCalculator
{
    private int $taxRate = 23;

    private float|int $total = 0;

    private string $netAmount = '';

    private string $vatAmount = '';

    public function calculateOrderTax(Order $order): void
    {
        $this->calculateTax($order->getAmount());

        $order->setNetAmount((int)$this->getNetAmount());
        $order->setVatAmount((int)$this->getVatAmount());
    }

    public function calculateTax(float|int $total): void
    {
        try {
            $this->total = $total;
            $vatDivisor = (string)(1 + ($this->taxRate / 100));
            $this->netAmount = bcdiv((string)$this->total, $vatDivisor);

            $this->vatAmount = bcsub((string)$this->total, $this->netAmount);
        } catch (DivisionByZeroError) {
            // no chance to throw this error, but we need to handle that for static analysis and coverage.
        }
    }

    public function getNetAmount(bool $humanFriendly = false): string
    {
        if ($humanFriendly) {
            return number_format((int)$this->netAmount / 100, 2, '.', ' ');
        }

        return $this->netAmount;
    }

    public function getVatAmount(bool $humanFriendly = false): string
    {
        if ($humanFriendly) {
            return number_format((int)$this->vatAmount / 100, 2, '.', ' ');
        }

        return $this->vatAmount;
    }
}
