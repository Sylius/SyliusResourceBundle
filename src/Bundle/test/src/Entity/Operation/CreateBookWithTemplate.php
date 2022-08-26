<?php

declare(strict_types=1);

namespace App\Entity\Operation;

use App\Entity\Book;
use Sylius\Component\Resource\Metadata\Create;

#[Create(template: 'book/create.html.twig', resource: 'app.book')]
final class CreateBookWithTemplate extends Book
{
}
