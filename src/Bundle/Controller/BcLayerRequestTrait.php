<?php

namespace Sylius\Bundle\ResourceBundle\Controller;

use Sylius\Resource\Exception\LogicException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Copied from symfony/http-request 7.4.
 * It's only used as bc-layer for the ParametersParser.
 *
 * @internal
 */
trait BcLayerRequestTrait
{
    public function getFromRequest(Request $request, string $key, mixed $default = null): mixed
    {
        if (!is_a($this::class, ParametersParser::class, true)) {
            throw new LogicException(sprintf(
                'You can only use "%s" trait as a bc-layer of the "%s. But "%s" provided.',
                self::class,
                ParametersParser::class,
                $this::class,
            ));
        }

        if ($request !== $result = $request->attributes->get($key, $this)) {
            return $result;
        }

        if ($request->query->has($key)) {
            return $request->query->all()[$key];
        }

        if ($request->request->has($key)) {
            return $request->request->all()[$key];
        }

        return $default;
    }
}
