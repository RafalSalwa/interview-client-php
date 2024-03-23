<?php

declare(strict_types=1);

namespace App\Messenger;

use App\Messenger\Message\AMQPMessage;
use Exception;
use JsonException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use function array_merge;
use function json_encode;
use function serialize;
use const JSON_THROW_ON_ERROR;

final class ExternalJsonMessageSerializer implements SerializerInterface
{
    // phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
    public function decode(array $encodedEnvelope): Envelope
    {
        return new Envelope($encodedEnvelope['body']);
    }

    /**
     * @return array<string,string>
     *
     * @throws JsonException
     */
    public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();
        if (false === $message instanceof AMQPMessage) {
            throw new Exception('Unsupported message class');
        }

        $allStamps = [];
        foreach ($envelope->all() as $stamps) {
            $allStamps = array_merge($allStamps, $stamps);
        }

        return [
            'body' => json_encode($message, JSON_THROW_ON_ERROR),
            'headers' => [
                'stamps' => serialize($allStamps),
            ],
        ];
    }
}
