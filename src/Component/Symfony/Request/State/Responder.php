<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Component\Resource\Symfony\Request\State;

use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\RequestOption;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\State\ResponderInterface;

final class Responder implements ResponderInterface
{
    private const RESPONDER_HTML = 'sylius.state_responder.html';

    private const RESPONDER_API = 'sylius.state_responder.api';

    public function __construct(private ContainerInterface $locator)
    {
    }

    public function respond(mixed $data, Operation $operation, Context $context): mixed
    {
        $request = $context->get(RequestOption::class)?->request();

        if (null === $request) {
            return null;
        }

        $format = $request->getRequestFormat();

        if ('html' === $format) {
            if (!$this->locator->has(self::RESPONDER_HTML)) {
                throw new \LogicException(sprintf('Responder "%s" was not found but it should.', self::RESPONDER_HTML));
            }

            /** @var ResponderInterface $responder */
            $responder = $this->locator->get(self::RESPONDER_HTML);

            return $responder->respond($data, $operation, $context);
        }

        if (!$this->locator->has(self::RESPONDER_API)) {
            throw new \LogicException(sprintf('Responder "%s" was not found but it should.', self::RESPONDER_API));
        }

        /** @var ResponderInterface $responder */
        $responder = $this->locator->get(self::RESPONDER_API);

        return $responder->respond($data, $operation, $context);
    }
}
