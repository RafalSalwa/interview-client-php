<?php

declare(strict_types=1);

namespace App\ValueObject\GRPC;

use stdClass;

use const Grpc\STATUS_OK;

/**
 * Converts GRPC response array into more readable form.
 *
 * @see \App\Tests\ValueObject\GRPC\StatusResponseTest
 */
final readonly class StatusResponse
{
    private int $code;

    private string $details;

    public function __construct(stdClass $class)
    {
        $this->code = $class->code;
        $this->details = $class->details;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getDetails(): string
    {
        return $this->details;
    }

    public function isOk(): bool
    {
        return STATUS_OK === $this->code;
    }
}
