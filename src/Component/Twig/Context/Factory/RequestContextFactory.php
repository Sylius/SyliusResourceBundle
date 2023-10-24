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

namespace Sylius\Component\Resource\Twig\Context\Factory;

use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Symfony\Component\Form\FormInterface;

final class RequestContextFactory implements ContextFactoryInterface
{
    public function __construct(private ContextFactoryInterface $decorated)
    {
    }

    public function create(mixed $data, Operation $operation, Context $context): array
    {
        $twigContext = $this->decorated->create($data, $operation, $context);

        $request = $context->get(RequestOption::class)?->request();

        if (null === $request) {
            return $twigContext;
        }

        /** @var FormInterface|null $form */
        $form = $request->attributes->get('form');

        if (null === $form) {
            return $twigContext;
        }

        return array_merge($twigContext, ['form' => $form->createView()]);
    }
}
