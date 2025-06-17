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
use App\Conference\Entity\Speaker;
use App\Conference\Factory\SpeakerFactory;
use Coduo\PHPMatcher\Backtrace\VoidBacktrace;
use Coduo\PHPMatcher\Matcher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class SpeakerUiTest extends ApiTestCase
{
    use Factories;
    use ResetDatabase;

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

        $this->assertResponseCode($response, Response::HTTP_OK);
        $content = $response->getContent();

        $this->assertStringContainsString('<td>Francis Hilaire</td>', $content);
        $this->assertStringContainsString('<td>Gregor Šink</td>', $content);
    }

    /** @test */
    public function it_allows_accessing_speaker_creation_page(): void
    {
        $this->client->request('GET', '/admin/speakers/new');

        $this->assertResponseCode($this->client->getResponse(), Response::HTTP_OK);
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

        $speaker->_refresh();
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

    protected function buildMatcher(): Matcher
    {
        return $this->matcherFactory->createMatcher(new VoidBacktrace());
    }
}
