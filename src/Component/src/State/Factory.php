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

namespace Sylius\Resource\State;

use Psr\Container\ContainerInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Factory\FactoryInterface as ResourceFactoryInterface;
use Sylius\Resource\Metadata\FactoryAwareOperationInterface;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Symfony\ExpressionLanguage\ArgumentParserInterface;
use Webmozart\Assert\Assert;

/**
 * @experimental
 */
final class Factory implements FactoryInterface
{
    public function __construct(
        private ContainerInterface $locator,
        private ArgumentParserInterface $argumentParser,
    ) {
    }

    public function create(Operation $operation, Context $context): ?object
    {
        if (!$operation instanceof FactoryAwareOperationInterface) {
            return null;
        }

        $factory = $operation->getFactory();

        if (!$factory) {
            return null;
        }

        $arguments = $this->parseArgumentValues($operation->getFactoryArguments() ?? []);

        if (\is_callable($factory)) {
            return $factory(...$arguments);
        }

        if (!$this->locator->has($factory)) {
            throw new \RuntimeException(sprintf('Factory "%s" not found on operation "%s"', $factory, $operation->getName() ?? ''));
        }

        $factoryInstance = $this->locator->get($factory);
        Assert::isInstanceOf($factoryInstance, ResourceFactoryInterface::class);

        $factoryMethod = $operation->getFactoryMethod();

        if (null === $factoryMethod) {
            throw new \RuntimeException(sprintf('No Factory method was configured on operation "%s"', $operation->getName() ?? ''));
        }

        return $factoryInstance->$factoryMethod(...$arguments);
    }

    private function parseArgumentValues(array $arguments): array
    {
        foreach ($arguments as $key => $value) {
            if (!str_starts_with($value, '@=')) {
                $value = '@=' . $value;
                trigger_deprecation('sylius/resource-bundle', '1.14', 'You passed "%s" as a string value in your repository arguments. If this is a value that needs to be parsed using the expression language, please prefix your string with "@=". In your case, use "@=%s"."', $value, $value);
            }

            if (!str_starts_with($value, '@=')) {
                $arguments[$key] = $value;

                continue;
            }

            $value = substr($value, 2);
            $arguments[$key] = $this->argumentParser->parseExpression($value);
        }

        return $arguments;
    }
}
