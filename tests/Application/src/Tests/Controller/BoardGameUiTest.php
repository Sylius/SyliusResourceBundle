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

namespace App\Tests\Controller;

use ApiTestCase\ApiTestCase;
use App\BoardGameBlog\Domain\Model\BoardGame;
use App\BoardGameBlog\Domain\Repository\BoardGameRepositoryInterface;
use App\BoardGameBlog\Domain\ValueObject\BoardGameName;
use App\BoardGameBlog\Infrastructure\Foundry\Factory\BoardGameFactory;
use Coduo\PHPMatcher\Backtrace\VoidBacktrace;
use Coduo\PHPMatcher\Matcher;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;

final class BoardGameUiTest extends ApiTestCase
{
    use Factories;

    /** @test */
    public function it_allows_showing_a_board_game(): void
    {
        $boardGame = BoardGameFactory::new()
            ->withName(new BoardGameName('Ticket to Ride'))
            ->create()
        ;

        $this->client->request('GET', '/admin/board-games/' . $boardGame->id());
        $response = $this->client->getResponse();

        $this->assertResponseCode($response, Response::HTTP_OK);
        $content = $response->getContent();
        $this->assertStringContainsString(sprintf('ID: %s', $boardGame->id()), $content);
        $this->assertStringContainsString('Name: Ticket to Ride', $content);
    }

    /** @test */
    public function it_allows_browsing_board_games(): void
    {
        $stoneAgeBoardGame = BoardGameFactory::new()
            ->withName(new BoardGameName('Stone Age'))
            ->create()
        ;

        $ticketToRideBoardGame = BoardGameFactory::new()
            ->withName(new BoardGameName('Ticket to Ride'))
            ->create()
        ;

        $this->client->request('GET', '/admin/board-games');
        $response = $this->client->getResponse();

        $this->assertResponseCode($response, Response::HTTP_OK);
        $content = $response->getContent();

        $this->assertStringContainsString('<td>Stone Age</td>', $content);
        $this->assertStringContainsString(sprintf('<a href="/admin/board-games/%s">Show</a>', $stoneAgeBoardGame->id()), $content);
        $this->assertStringContainsString(sprintf('<a href="/admin/board-games/%s/edit">Edit</a>', $stoneAgeBoardGame->id()), $content);
        $this->assertStringContainsString(sprintf('<form action="/admin/board-games/%s/delete" method="post">', $stoneAgeBoardGame->id()), $content);

        $this->assertStringContainsString('<td>Ticket to Ride</td>', $content);
        $this->assertStringContainsString(sprintf('<a href="/admin/board-games/%s">Show</a>', $ticketToRideBoardGame->id()), $content);
        $this->assertStringContainsString(sprintf('<a href="/admin/board-games/%s/edit">Edit</a>', $ticketToRideBoardGame->id()), $content);
        $this->assertStringContainsString(sprintf('<form action="/admin/board-games/%s/delete" method="post">', $ticketToRideBoardGame->id()), $content);
    }

    /** @test */
    public function it_allows_accessing_board_game_creation_page(): void
    {
        $this->client->request('GET', '/admin/board-games/new');

        $this->assertResponseCode($this->client->getResponse(), Response::HTTP_OK);
    }

    /** @test */
    public function it_allows_creating_a_board_game(): void
    {
        $this->client->request('GET', '/admin/board-games/new');
        $this->client->submitForm('Create', [
            'board_game[name]' => 'Puerto Rico',
        ]);

        $this->assertResponseRedirects(null, expectedCode: Response::HTTP_FOUND);

        /** @var BoardGame $boardGame */
        $boardGame = static::getContainer()->get(BoardGameRepositoryInterface::class)->findOneBy(['name.value' => 'Puerto Rico']);

        $this->assertNotNull($boardGame);
        $this->assertSame('Puerto Rico', (string) $boardGame->name());
    }

    /** @test */
    public function it_does_not_allow_to_create_a_board_game_if_there_is_a_validation_error(): void
    {
        $this->client->request('GET', '/admin/board-games/new');
        $this->client->submitForm('Create', [
            'board_game[name]' => null,
        ]);

        $this->assertResponseCode($this->client->getResponse(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_allows_updating_a_board_game(): void
    {
        $boardGame = BoardGameFactory::createOne();

        $this->client->request('GET', '/admin/board-games/' . $boardGame->id() . '/edit');
        $this->client->submitForm('Save changes', [
            'board_game[name]' => 'Puerto Rico',
        ]);

        $this->assertResponseRedirects(null, expectedCode: Response::HTTP_FOUND);

        $boardGame->_refresh();
        $this->assertSame('Puerto Rico', (string) $boardGame->name());
    }

    /** @test */
    public function it_does_not_allow_to_update_a_board_game_if_there_is_a_validation_error(): void
    {
        $boardGame = BoardGameFactory::createOne();

        $this->client->request('GET', '/admin/board-games/' . $boardGame->id() . '/edit');
        $this->client->submitForm('Save changes', [
            'board_game[name]' => null,
        ]);

        $this->assertResponseCode($this->client->getResponse(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_allows_deleting_a_board_game(): void
    {
        BoardGameFactory::createOne();

        $this->client->request('GET', '/admin/board-games');
        $this->client->submitForm('Delete');

        $this->assertResponseRedirects(null, expectedCode: Response::HTTP_FOUND);

        /** @var BoardGame[] $boardGames */
        $boardGames = static::getContainer()->get(BoardGameRepositoryInterface::class)->findAll();

        $this->assertEmpty($boardGames);
    }

    protected function buildMatcher(): Matcher
    {
        return $this->matcherFactory->createMatcher(new VoidBacktrace());
    }
}
