<?php

declare(strict_types=1);

namespace App\Entity\Operation;

use App\Entity\Book;
use Sylius\Component\Resource\Metadata\Create;

#[Create(criteria: ['library' => '$libraryId'], resource: 'app.book')]
final class CreateBookWithCriteria extends Book
{
}
