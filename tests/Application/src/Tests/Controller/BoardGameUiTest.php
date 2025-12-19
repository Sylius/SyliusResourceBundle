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

use App\BoardGameBlog\Domain\Model\BoardGame;
use App\BoardGameBlog\Domain\Repository\BoardGameRepositoryInterface;
use App\BoardGameBlog\Domain\ValueObject\BoardGameName;
use App\BoardGameBlog\Infrastructure\Foundry\Factory\BoardGameFactory;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use function Zenstruck\Foundry\Persistence\refresh;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class BoardGameUiTest extends WebTestCase
{
    use Factories;
    use ResetDatabase;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    #[Test]
    public function it_allows_showing_a_board_game(): void
    {
        $boardGame = BoardGameFactory::new()
            ->withName(new BoardGameName('Ticket to Ride'))
            ->create()
        ;

        $this->client->request('GET', '/admin/board-games/' . $boardGame->id());
        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $content = $response->getContent();
        $this->assertStringContainsString(sprintf('ID: %s', $boardGame->id()), $content);
        $this->assertStringContainsString('Name: Ticket to Ride', $content);
    }

    #[Test]
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

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
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

    #[Test]
    public function it_allows_accessing_board_game_creation_page(): void
    {
        $this->client->request('GET', '/admin/board-games/new');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    #[Test]
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

    #[Test]
    public function it_does_not_allow_to_create_a_board_game_if_there_is_a_validation_error(): void
    {
        $this->client->request('GET', '/admin/board-games/new');
        $this->client->submitForm('Create', [
            'board_game[name]' => null,
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    #[Test]
    public function it_allows_updating_a_board_game(): void
    {
        $boardGame = BoardGameFactory::createOne();

        $this->client->request('GET', '/admin/board-games/' . $boardGame->id() . '/edit');
        $this->client->submitForm('Save changes', [
            'board_game[name]' => 'Puerto Rico',
        ]);

        $this->assertResponseRedirects(null, expectedCode: Response::HTTP_FOUND);

        $boardGame = refresh($boardGame);
        $this->assertSame('Puerto Rico', (string) $boardGame->name());
    }

    #[Test]
    public function it_does_not_allow_to_update_a_board_game_if_there_is_a_validation_error(): void
    {
        $boardGame = BoardGameFactory::createOne();

        $this->client->request('GET', '/admin/board-games/' . $boardGame->id() . '/edit');
        $this->client->submitForm('Save changes', [
            'board_game[name]' => null,
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    #[Test]
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
}
