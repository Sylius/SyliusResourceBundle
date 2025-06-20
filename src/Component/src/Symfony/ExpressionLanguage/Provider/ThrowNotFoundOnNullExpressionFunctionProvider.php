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

namespace Sylius\Resource\Symfony\ExpressionLanguage\Provider;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ThrowNotFoundOnNullExpressionFunctionProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions(): array
    {
        return [
            new ExpressionFunction(
                'throw_not_found_on_null',
                function (string $value, string ...$args): string {
                    $message = $args[0] ?? '';

                    return sprintf(
                        '(null !== %1$s) ? %1$s : throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException(%2$s)',
                        $value,
                        $message,
                    );
                },
                function (array $arguments, mixed $value, ?string $message = null): mixed {
                    if (null === $value) {
                        throw new NotFoundHttpException($message ?? '');
                    }

                    return $value;
                },
            ),
        ];
    }
}
