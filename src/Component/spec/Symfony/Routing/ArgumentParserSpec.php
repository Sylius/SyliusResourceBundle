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

namespace spec\Sylius\Component\Resource\Symfony\Routing;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Symfony\ExpressionLanguage\VariablesCollectionInterface;
use Sylius\Component\Resource\Symfony\Routing\ArgumentParser;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class ArgumentParserSpec extends ObjectBehavior
{
    function let(VariablesCollectionInterface $variablesCollection): void
    {
        $this->beConstructedWith(new ExpressionLanguage(), $variablesCollection);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ArgumentParser::class);
    }

    function it_parses_resource_argument_with_public_property(
        \stdClass $data,
        VariablesCollectionInterface $variablesCollection,
    ): void {
        $data->code = 'xyz';
        $resource = new Resource(alias: 'app.book');

        $variablesCollection->getVariables()->willReturn([]);

        $this->parseExpression('resource.code', $resource, $data)->shouldReturn('xyz');
    }

    function it_parses_resource_argument_via_a_getter(
        VariablesCollectionInterface $variablesCollection,
    ): void {
        $data = new BoardGame('uid');
        $resource = new Resource(alias: 'app.board_game');

        $variablesCollection->getVariables()->willReturn([]);

        $this->parseExpression('resource.id()', $resource, $data)->shouldReturn('uid');
    }

    function it_parses_resource_argument_with_resource_name(
        \stdClass $data,
        VariablesCollectionInterface $variablesCollection,
    ): void {
        $data->code = 'xyz';
        $resource = new Resource(alias: 'app.book', name: 'book');

        $variablesCollection->getVariables()->willReturn([]);

        $this->parseExpression('book.code', $resource, $data)->shouldReturn('xyz');
    }

    function it_merges_variables_collection_with_resource_name(
        \stdClass $data,
        VariablesCollectionInterface $variablesCollection,
    ): void {
        $data->code = 'xyz';
        $resource = new Resource(alias: 'app.book', name: 'book');

        $variablesCollection->getVariables()->willReturn(['expected_code' => 'xyz']);

        $this->parseExpression('book.code === expected_code', $resource, $data)->shouldReturn(true);
    }
}

final class BoardGame
{
    public function __construct(private string $id)
    {
    }

    public function id(): string
    {
        return $this->id;
    }
}
