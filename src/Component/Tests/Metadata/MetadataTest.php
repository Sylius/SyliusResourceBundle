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

namespace Sylius\Component\Resource\Tests\Metadata;

use Doctrine\Inflector\InflectorFactory;
use Doctrine\Inflector\Rules\Patterns;
use Doctrine\Inflector\Rules\Ruleset;
use Doctrine\Inflector\Rules\Substitution;
use Doctrine\Inflector\Rules\Substitutions;
use Doctrine\Inflector\Rules\Transformations;
use Doctrine\Inflector\Rules\Word;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Metadata\Metadata;

/**
 * @psalm-suppress PropertyNotSetInConstructor Having some issues with custom PHPUnit annotations
 */
final class MetadataTest extends TestCase
{
    /**
     * @test
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function it_uses_a_custom_inflector(): void
    {
        $factory = InflectorFactory::create();
        $factory->withPluralRules(new Ruleset(
            new Transformations(),
            new Patterns(),
            new Substitutions(new Substitution(new Word('taxon'), new Word('taxons')))
        ));
        $inflector = $factory->build();

        Metadata::setInflector($inflector);

        $metadata = Metadata::fromAliasAndConfiguration('app.taxon', ['driver' => 'whatever']);

        Assert::assertSame('taxons', $metadata->getPluralName());
    }

    /** @test */
    public function it_uses_a_default_inflector(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.taxon', ['driver' => 'whatever']);

        Assert::assertSame('taxa', $metadata->getPluralName());
    }
}
