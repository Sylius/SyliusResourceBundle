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

namespace App\Tests\Validator;

use ApiTestCase\JsonApiTestCase;
use App\Entity\Book;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class TranslatableValidatorTest extends JsonApiTestCase
{
    /** @test */
    public function it_fails_validation_with_empty_locale(): void
    {
        $this->loadFixturesFromFile('books.yml');
        $book = $this->getBookBy();
        $book->getTranslation('pl_PL')->setLocale('');

        $errors = $this->getValidator()->validate($book, null, ['sylius']);
        $this->assertCount(1, $errors);
        $this->assertSame('sylius.resource.translation.locale.not_blank', $errors->get(0)->getMessageTemplate());
    }

    /** @test */
    public function it_fails_validation_with_invalid_locale(): void
    {
        $this->loadFixturesFromFile('books.yml');
        $book = $this->getBookBy();
        $book->getTranslation('pl_PL')->setLocale('invalid');

        $errors = $this->getValidator()->validate($book, null, ['sylius']);
        $this->assertCount(1, $errors);
        $this->assertSame('sylius.resource.translation.locale.invalid', $errors->get(0)->getMessageTemplate());
    }

    /** @test */
    public function it_fails_validation_with_not_unique_locale(): void
    {
        $this->loadFixturesFromFile('books.yml');
        $book = $this->getBookBy();
        $book->getTranslation('pl_PL')->setLocale('en_US');

        $errors = $this->getValidator()->validate($book, null, ['sylius']);
        $this->assertCount(1, $errors);
        $this->assertSame('sylius.resource.translation.locale.unique', $errors->get(0)->getMessageTemplate());
    }

    private function getValidator(): ValidatorInterface
    {
        return self::getContainer()->get('validator');
    }

    private function getBookBy(array $criteria = ['author' => 'J.R.R. Tolkien']): ?Book
    {
        return self::getContainer()->get('app.repository.book')->findOneBy($criteria);
    }
}
