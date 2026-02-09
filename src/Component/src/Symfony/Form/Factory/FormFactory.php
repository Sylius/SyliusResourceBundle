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

namespace Sylius\Resource\Symfony\Form\Factory;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Exception\InvalidArgumentException;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Symfony\ExpressionLanguage\ArgumentParserInterface;
use Symfony\Component\Form\FormFactoryInterface as SymfonyFormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @experimental
 */
final class FormFactory implements FormFactoryInterface
{
    public function __construct(
        private readonly SymfonyFormFactoryInterface $formFactory,
        private readonly ArgumentParserInterface $argumentParser,
    ) {
    }

    public function create(Operation $operation, Context $context, mixed $data = null): FormInterface
    {
        $formType = $operation->getFormType();
        $formOptions = $this->parseFormOptions($operation->getFormOptions() ?? []);

        if (null === $formType) {
            throw new \RuntimeException(sprintf('Operation "%s" has no configured form type.', $operation->getName() ?? ''));
        }

        $request = $context->get(RequestOption::class)?->request();

        if ('html' === $request?->getRequestFormat()) {
            return $this->formFactory->create($formType, $data, $formOptions);
        }

        return $this->formFactory->createNamed('', $formType, $data, array_merge($formOptions, ['csrf_protection' => false]));
    }

    /**
     * @param array<string, mixed> $formOptions
     *
     * @return array<string, mixed>
     */
    private function parseFormOptions(array $formOptions): array
    {
        foreach ($formOptions as $key => $value) {
            if (\is_array($value)) {
                $formOptions[$key] = $this->parseFormOptions($value);

                continue;
            }

            if (!\is_scalar($value)) {
                throw new InvalidArgumentException(sprintf('Parameter "%s" should be a scalar or an array.', $key));
            }

            if (!is_string($value) || !str_starts_with($value, '@=')) {
                continue;
            }

            $value = substr($value, 2);

            $formOptions[$key] = $this->argumentParser->parseExpression($value);
        }

        return $formOptions;
    }
}
