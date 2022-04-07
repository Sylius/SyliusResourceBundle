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

namespace spec\Sylius\Bundle\ResourceBundle\DataTransformer;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\DataTransformer\ChainDataTransformer;
use Sylius\Bundle\ResourceBundle\DataTransformer\ChainDataTransformerInterface;
use Sylius\Bundle\ResourceBundle\DataTransformer\DataTransformerInterface;

final class ChainDataTransformerSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(ChainDataTransformer::class);
    }

    function it_implements_an_interface(): void
    {
        $this->shouldImplement(ChainDataTransformerInterface::class);
    }

    function it_adds_data_transformers(
        DataTransformerInterface $firstDataTransformer,
        DataTransformerInterface $secondDataTransformer
    ): void {
        $this->addDataTransformer($firstDataTransformer);
        $this->addDataTransformer($secondDataTransformer);
    }

    function it_transforms_data_to_an_another_object(
        \stdClass $object,
        \stdClass $output,
        DataTransformerInterface $firstDataTransformer,
        DataTransformerInterface $secondDataTransformer,
        RequestConfiguration $configuration
    ): void {
        $this->addDataTransformer($firstDataTransformer);
        $this->addDataTransformer($secondDataTransformer);

        $firstDataTransformer->supportsTransformation($object, \stdClass::class, $configuration)->willReturn(false);
        $secondDataTransformer->supportsTransformation($object, \stdClass::class, $configuration)->willReturn(true);

        $secondDataTransformer->transform($object, \stdClass::class, $configuration)->willReturn($output);

        $this->transform($object, \stdClass::class, $configuration)->shouldReturn($output);
    }

    function it_returns_null_when_no_data_transformer_was_found(
        \stdClass $object,
        RequestConfiguration $configuration
    ): void {
        $this->transform($object, \stdClass::class, $configuration)->shouldReturn(null);
    }
}
