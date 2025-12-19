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

use App\Conference\Entity\Speaker;
use App\Conference\Factory\SpeakerFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use function Zenstruck\Foundry\Persistence\refresh;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class SpeakerUiTest extends WebTestCase
{
    use Factories;
    use ResetDatabase;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = $this->createClient();
    }

    /** @test */
    public function it_allows_browsing_speakers(): void
    {
        SpeakerFactory::new()
            ->withFirstName('Francis')
            ->withLastName('Hilaire')
            ->create()
        ;

        SpeakerFactory::new()
            ->withFirstName('Gregor')
            ->withLastName('Šink')
            ->create()
        ;

        $this->client->request('GET', '/admin/speakers');
        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $content = $response->getContent();

        $this->assertStringContainsString('<td>Francis Hilaire</td>', $content);
        $this->assertStringContainsString('<td>Gregor Šink</td>', $content);
    }

    /** @test */
    public function it_allows_accessing_speaker_creation_page(): void
    {
        $this->client->request('GET', '/admin/speakers/new');

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
    }

    /** @test */
    public function it_allows_creating_a_speaker(): void
    {
        $this->client->request('GET', '/admin/speakers/new');
        $this->client->submitForm('Create', [
            'speaker[firstName]' => 'Francis',
            'speaker[lastName]' => 'Hilaire',
        ]);

        $this->assertResponseRedirects(null, expectedCode: Response::HTTP_FOUND);

        /** @var Speaker|null $speaker */
        $speaker = static::getContainer()->get(EntityManagerInterface::class)->getRepository(Speaker::class)->findOneBy(['firstName' => 'Francis']);

        $this->assertNotNull($speaker);
        $this->assertSame('Francis Hilaire', $speaker->getFullName());
    }

    /** @test */
    public function it_allows_updating_a_speaker(): void
    {
        $speaker = SpeakerFactory::createOne();

        $this->client->request('GET', '/admin/speakers/' . $speaker->getId() . '/edit');
        $this->client->submitForm('Save changes', [
            'speaker[firstName]' => 'Francis',
            'speaker[lastName]' => 'Hilaire',
        ]);

        $this->assertResponseRedirects(null, expectedCode: Response::HTTP_FOUND);

        $speaker = refresh($speaker);
        $this->assertSame('Francis Hilaire', $speaker->getFullName());
    }

    /** @test */
    public function it_allows_deleting_a_speaker(): void
    {
        SpeakerFactory::createOne();

        $this->client->request('GET', '/admin/speakers');
        $this->client->submitForm('Delete');

        $this->assertResponseRedirects(null, expectedCode: Response::HTTP_FOUND);

        /** @var Speaker[] $speakers */
        $speakers = static::getContainer()->get(EntityManagerInterface::class)->getRepository(Speaker::class)->findAll();

        $this->assertEmpty($speakers);
    }
}
