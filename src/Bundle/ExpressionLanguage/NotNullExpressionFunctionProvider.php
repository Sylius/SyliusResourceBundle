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

namespace Sylius\Bundle\ResourceBundle\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class NotNullExpressionFunctionProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions(): array
    {
        return [
            new ExpressionFunction(
                'notFoundOnNull',
                /**
                 * @param mixed $result
                 */
                function ($result): string {
                    return sprintf('(null !== %1$s) ? %1$s : throw new NotFoundHttpException(\'Requested page is invalid.\')', $result);
                },
                /**
                 * @param mixed $arguments
                 * @param mixed $result
                 *
                 * @return mixed
                 */
                function ($arguments, $result) {
                    if (null === $result) {
                        throw new NotFoundHttpException('Requested page is invalid.');
                    }

                    return $result;
                },
            ),
        ];
    }
}
