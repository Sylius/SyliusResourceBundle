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
use App\Tests\Trait\UiTestTrait;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class SpeakerUiTest extends WebTestCase
{
    use Factories;
    use ResetDatabase;
    use UiTestTrait;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    #[Test]
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

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $content = $this->client->getResponse()->getContent();
        $this->assertStringContainsString('<td>Francis Hilaire</td>', $content);
        $this->assertStringContainsString('<td>Gregor Šink</td>', $content);
    }

    #[Test]
    public function it_allows_accessing_speaker_creation_page(): void
    {
        $this->client->request('GET', '/admin/speakers/new');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    #[Test]
    public function it_allows_creating_a_speaker(): void
    {
        $this->client->request('GET', '/admin/speakers/new');

        $this->submitForm('Create', [
            'speaker[firstName]' => 'Francis',
            'speaker[lastName]' => 'Hilaire',
        ]);

        $this->assertResponseRedirects(null, Response::HTTP_FOUND);

        /** @var Speaker|null $speaker */
        $speaker = self::getContainer()->get(EntityManagerInterface::class)->getRepository(Speaker::class)->findOneBy(['firstName' => 'Francis']);

        $this->assertNotNull($speaker);
        $this->assertSame('Francis Hilaire', $speaker->getFullName());
    }

    #[Test]
    public function it_allows_updating_a_speaker(): void
    {
        $speaker = SpeakerFactory::createOne();

        $this->client->request('GET', '/admin/speakers/' . $speaker->getId() . '/edit');

        $this->submitForm('Save changes', [
            'speaker[firstName]' => 'Francis',
            'speaker[lastName]' => 'Hilaire',
        ]);

        $this->assertResponseRedirects(null, Response::HTTP_FOUND);

        $speaker->_refresh();
        $this->assertSame('Francis Hilaire', $speaker->getFullName());
    }

    #[Test]
    public function it_allows_deleting_a_speaker(): void
    {
        SpeakerFactory::createOne();

        $this->client->request('GET', '/admin/speakers');

        $this->submitForm('Delete');

        $this->assertResponseRedirects(null, Response::HTTP_FOUND);

        /** @var Speaker[] $speakers */
        $speakers = self::getContainer()->get(EntityManagerInterface::class)->getRepository(Speaker::class)->findAll();

        $this->assertEmpty($speakers);
    }
}
