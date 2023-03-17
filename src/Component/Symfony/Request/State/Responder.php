<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Component\Resource\Symfony\Request\State;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\RequestOption;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\State\ResponderInterface;

final class Responder implements ResponderInterface
{
    public function __construct(
        private ResponderInterface $htmlResponder,
        private ResponderInterface $apiResponder,
    ) {
    }

    public function respond(mixed $data, Operation $operation, Context $context): mixed
    {
        $request = $context->get(RequestOption::class)?->request();

        if (null === $request) {
            return null;
        }

        $format = $request->getRequestFormat();

        if ('html' === $format) {
            return $this->htmlResponder->respond($data, $operation, $context);
        }

        return $this->apiResponder->respond($data, $operation, $context);
    }
}
