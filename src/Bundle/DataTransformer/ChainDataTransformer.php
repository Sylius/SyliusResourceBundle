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

namespace Sylius\Bundle\ResourceBundle\DataTransformer;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;

final class ChainDataTransformer implements ChainDataTransformerInterface
{
    /** @var DataTransformerInterface[] */
    private array $dataTransformers;

    public function __construct(array $dataTransformers = [])
    {
        $this->dataTransformers = $dataTransformers;
    }

    public function addDataTransformer(DataTransformerInterface $dataTransformer): void
    {
        $this->dataTransformers[] = $dataTransformer;
    }

    public function transform(object $data, string $to, RequestConfiguration $configuration): ?object
    {
        $dataTransformer = $this->getDataTransformer($data, $to, $configuration);

        if (null === $dataTransformer) {
            return null;
        }

        return $dataTransformer->transform($data, $to, $configuration);
    }

    /**
     * @psalm-param class-string $to
     */
    private function getDataTransformer(object $data, string $to, RequestConfiguration $configuration): ?DataTransformerInterface
    {
        foreach ($this->dataTransformers as $dataTransformer) {
            if ($dataTransformer->supportsTransformation($data, $to, $configuration)) {
                return $dataTransformer;
            }
        }

        return null;
    }
}
