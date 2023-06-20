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

namespace App\Tests\Controller;

use ApiTestCase\ApiTestCase;
use App\BoardGameBlog\Domain\Model\BoardGame;
use App\BoardGameBlog\Domain\Repository\BoardGameRepositoryInterface;
use Coduo\PHPMatcher\Backtrace\VoidBacktrace;
use Coduo\PHPMatcher\Matcher;
use Symfony\Component\HttpFoundation\Response;

final class BoardGameUiTest extends ApiTestCase
{
    /** @test */
    public function it_allows_showing_a_board_game(): void
    {
        $boardGames = $this->loadFixturesFromFile('board_games.yml');

        $this->client->request('GET', '/admin/board-games/' . $boardGames['ticket_to_ride']->id());
        $response = $this->client->getResponse();

        $this->assertResponseCode($response, Response::HTTP_OK);
        $content = $response->getContent();
        $this->assertStringContainsString(sprintf('ID: %s', $boardGames['ticket_to_ride']->id()), $content);
        $this->assertStringContainsString('Name: Ticket to Ride', $content);
    }

    /** @test */
    public function it_allows_browsing_board_games(): void
    {
        $boardGames = $this->loadFixturesFromFile('board_games.yml');

        $this->client->request('GET', '/admin/board-games');
        $response = $this->client->getResponse();

        $this->assertResponseCode($response, Response::HTTP_OK);
        $content = $response->getContent();

        $this->assertStringContainsString('<td>Stone Age</td>', $content);
        $this->assertStringContainsString(sprintf('<a href="/admin/board-games/%s">Show</a>', $boardGames['stone_age']->id()), $content);
        $this->assertStringContainsString(sprintf('<a href="/admin/board-games/%s/edit">Edit</a>', $boardGames['stone_age']->id()), $content);
        $this->assertStringContainsString(sprintf('<form action="/admin/board-games/%s" method="post">', $boardGames['stone_age']->id()), $content);

        $this->assertStringContainsString('<td>Ticket to Ride</td>', $content);
        $this->assertStringContainsString(sprintf('<a href="/admin/board-games/%s">Show</a>', $boardGames['ticket_to_ride']->id()), $content);
        $this->assertStringContainsString(sprintf('<a href="/admin/board-games/%s/edit">Edit</a>', $boardGames['ticket_to_ride']->id()), $content);
        $this->assertStringContainsString(sprintf('<form action="/admin/board-games/%s" method="post">', $boardGames['ticket_to_ride']->id()), $content);
    }

    /** @test */
    public function it_allows_creating_a_board_game(): void
    {
        $this->loadFixturesFromFile('board_games.yml');

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
        $this->loadFixturesFromFile('board_games.yml');

        $this->client->request('GET', '/admin/board-games/new');
        $this->client->submitForm('Create', [
            'board_game[name]' => null,
        ]);

        $this->assertResponseCode($this->client->getResponse(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_allows_updating_a_board_game(): void
    {
        $boardGames = $this->loadFixturesFromFile('board_games.yml');

        $this->client->request('GET', '/admin/board-games/' . $boardGames['stone_age']->id() . '/edit');
        $this->client->submitForm('Save changes', [
            'board_game[name]' => 'Puerto Rico',
        ]);

        $this->assertResponseRedirects(null, expectedCode: Response::HTTP_FOUND);

        /** @var BoardGame $boardGame */
        $boardGame = static::getContainer()->get(BoardGameRepositoryInterface::class)->findOneBy(['name.value' => 'Puerto Rico']);

        $this->assertNotNull($boardGame);
        $this->assertSame('Puerto Rico', (string) $boardGame->name());
    }

    /** @test */
    public function it_does_not_allow_to_update_a_board_game_if_there_is_a_validation_error(): void
    {
        $boardGames = $this->loadFixturesFromFile('board_games.yml');

        $this->client->request('GET', '/admin/board-games/' . $boardGames['stone_age']->id() . '/edit');
        $this->client->submitForm('Save changes', [
            'board_game[name]' => null,
        ]);

        $this->assertResponseCode($this->client->getResponse(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_allows_deleting_a_board_game(): void
    {
        $this->loadFixturesFromFile('single_board_game.yml');

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
